<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Perpustakaan;
use App\Models\TahunPelajaran;
use App\Models\KelasSiswa;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Imports\SiswaImport;

class ImportManagement extends Component
{
    use WithFileUploads;

    public $excelFile;
    public $tahun_pelajaran_id = '';
    public $importProgress = 0;
    public $importStatus = '';
    public $isImporting = false;

    protected $rules = [
        'tahun_pelajaran_id' => 'required|exists:tahun_pelajarans,id',
        'excelFile' => 'required|mimes:xlsx,xls,csv|max:10240'
    ];

    protected $messages = [
        'tahun_pelajaran_id.required' => 'Tahun pelajaran wajib dipilih.',
        'tahun_pelajaran_id.exists' => 'Tahun pelajaran yang dipilih tidak valid.',
        'excelFile.required' => 'File Excel wajib dipilih.',
        'excelFile.mimes' => 'File harus berformat Excel (.xlsx, .xls) atau CSV.',
        'excelFile.max' => 'Ukuran file maksimal 10MB.'
    ];

    public function mount()
    {
        // Initialize with active academic year
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        $this->tahun_pelajaran_id = $activeTahunPelajaran ? $activeTahunPelajaran->id : '';
    }

    public function resetForm()
    {
        $this->excelFile = null;
        $this->importProgress = 0;
        $this->importStatus = '';
        $this->isImporting = false;
        $this->resetErrorBag();
    }

    public function importExcel()
    {
        $this->validate();

        try {
            $this->isImporting = true;
            
            // Dispatch event to show loading in frontend
            $this->dispatch('import-started');
            
            $this->importStatus = 'Memproses file Excel...';
            $this->importProgress = 10;

            // Simpan file sementara
            $path = $this->excelFile->store('temp');
            
            $this->importProgress = 30;
            $this->importStatus = 'Membaca data Excel...';

            // Buat instance import dengan tahun pelajaran yang dipilih
            $import = new SiswaImport($this->tahun_pelajaran_id);
            
            $this->importProgress = 50;
            $this->importStatus = 'Memvalidasi data...';

            // Import data menggunakan Laravel Excel
            Excel::import($import, $path);
            
            $this->importProgress = 90;
            $this->importStatus = 'Menyelesaikan import...';

            // Hapus file sementara
            Storage::delete($path);

            $this->importProgress = 100;
            
            // Cek jika ada error
            $errors = $import->getErrors();
            $importedCount = $import->getImportedCount();
            
            if (!empty($errors)) {
                $errorMessage = "Import selesai dengan beberapa error:\n" . implode("\n", array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $errorMessage .= "\n... dan " . (count($errors) - 5) . " error lainnya";
                }
                $this->importStatus = "Import selesai: {$importedCount} data berhasil, " . count($errors) . " error";
                $this->dispatch('import-warning', $errorMessage);
            } else {
                $this->importStatus = "Import berhasil: {$importedCount} data diimport!";
                $this->dispatch('import-success', "Data berhasil diimport! Total: {$importedCount} siswa");
            }
            
            // Dispatch event to close loading
            $this->dispatch('import-completed');
            $this->isImporting = false;

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $this->dispatch('import-completed'); // Close loading on error
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }
            $this->importStatus = 'Error validasi data';
            $this->dispatch('import-error', "Error validasi:\n" . implode("\n", array_slice($errorMessages, 0, 5)));
            $this->isImporting = false;
        } catch (\Exception $e) {
            $this->dispatch('import-completed'); // Close loading on error
            $this->importStatus = 'Error: ' . $e->getMessage();
            $this->dispatch('import-error', $e->getMessage());
            $this->isImporting = false;
            // Hapus file jika ada error
            if (isset($path)) {
                Storage::delete($path);
            }
        }
    }

    public function downloadTemplate()
    {
        $templatePath = public_path('template_import_siswa.csv');
        
        if (file_exists($templatePath)) {
            return response()->download($templatePath, 'template_import_siswa.csv');
        } else {
            $this->dispatch('template-error', 'Template file tidak ditemukan.');
        }
    }

    public function render()
    {
        $tahunPelajaranOptions = TahunPelajaran::orderBy('nama_tahun_pelajaran', 'desc')->get();
        
        // Get statistics for selected academic year
        $statistics = [];
        if ($this->tahun_pelajaran_id) {
            $statistics = [
                'total_siswa' => Siswa::where('tahun_pelajaran_id', $this->tahun_pelajaran_id)->count(),
                'total_kelas' => Kelas::where('tahun_pelajaran_id', $this->tahun_pelajaran_id)->count(),
                'siswa_perpustakaan_aktif' => Siswa::where('tahun_pelajaran_id', $this->tahun_pelajaran_id)
                    ->whereHas('perpustakaan', function($q) {
                        $q->where('terpenuhi', true);
                    })->count(),
                'siswa_terdaftar_kelas' => KelasSiswa::where('tahun_pelajaran_id', $this->tahun_pelajaran_id)->count()
            ];
        }

        return view('livewire.import-management', [
            'tahunPelajaranOptions' => $tahunPelajaranOptions,
            'statistics' => $statistics
        ])->layout('layouts.app', [
            'title' => 'Import Data Excel',
            'page-title' => 'Import Data Excel'
        ]);
    }
}