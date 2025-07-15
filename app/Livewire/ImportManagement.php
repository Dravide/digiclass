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
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportManagement extends Component
{
    use WithFileUploads;

    public $excelFile;
    public $kelas_id = '';
    public $importProgress = 0;
    public $importStatus = '';
    public $isImporting = false;

    protected $rules = [
        'kelas_id' => 'required|exists:kelas,id',
        'excelFile' => 'required|mimes:xlsx,xls,csv|max:10240'
    ];

    protected $messages = [
        'kelas_id.required' => 'Kelas wajib dipilih.',
        'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
        'excelFile.required' => 'File Excel wajib dipilih.',
        'excelFile.mimes' => 'File harus berformat Excel (.xlsx, .xls) atau CSV.',
        'excelFile.max' => 'Ukuran file maksimal 10MB.'
    ];

    public function mount()
    {
        // Initialize with first available class from active academic year
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        if ($activeTahunPelajaran) {
            $firstKelas = Kelas::where('tahun_pelajaran_id', $activeTahunPelajaran->id)->first();
            $this->kelas_id = $firstKelas ? $firstKelas->id : '';
        }
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

            // Get selected class information
            $kelas = Kelas::with('tahunPelajaran')->find($this->kelas_id);
            if (!$kelas) {
                throw new \Exception('Kelas tidak ditemukan.');
            }

            // Simpan file sementara
            $path = $this->excelFile->store('temp');
            $fullPath = Storage::path($path);
            
            $this->importProgress = 30;
            $this->importStatus = 'Membaca data Excel...';

            // Load spreadsheet
            $spreadsheet = IOFactory::load($fullPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            $this->importProgress = 50;
            $this->importStatus = 'Memvalidasi dan memproses data...';

            $importedCount = 0;
            $errors = [];
            $header = array_shift($rows); // Remove header row
            
            // Expected columns: Nama Siswa, Jenis Kelamin, NISN, NIS, Status Perpustakaan
            $expectedColumns = ['nama_siswa', 'jk', 'nisn', 'nis', 'status_perpustakaan'];
            
            DB::beginTransaction();
            
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 because we removed header and Excel starts from 1
                
                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }
                    
                    // Validate required fields
                    if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3])) {
                        $errors[] = "Baris {$rowNumber}: Data wajib tidak lengkap (Nama, JK, NISN, NIS)";
                        continue;
                    }
                    
                    // Prepare siswa data
                    $siswaData = [
                        'nama_siswa' => trim($row[0]),
                        'jk' => strtoupper(trim($row[1])) === 'L' ? 'L' : 'P',
                        'nisn' => trim($row[2]),
                        'nis' => trim($row[3]),
                        'tahun_pelajaran_id' => $kelas->tahun_pelajaran_id,
                        'status' => Siswa::STATUS_AKTIF,
                        'keterangan' => Siswa::KETERANGAN_SISWA_BARU
                    ];
                    
                    // Check for duplicate NISN/NIS
                    $existingSiswa = Siswa::where('nisn', $siswaData['nisn'])
                        ->orWhere('nis', $siswaData['nis'])
                        ->first();
                        
                    if ($existingSiswa) {
                        $errors[] = "Baris {$rowNumber}: NISN/NIS sudah ada dalam database";
                        continue;
                    }
                    
                    // Create siswa
                    $siswa = Siswa::create($siswaData);
                    
                    // Create KelasSiswa relationship
                    KelasSiswa::create([
                        'tahun_pelajaran_id' => $kelas->tahun_pelajaran_id,
                        'siswa_id' => $siswa->id,
                        'kelas_id' => $kelas->id
                    ]);
                    
                    // Create Perpustakaan record
                    $statusPerpustakaan = isset($row[4]) ? trim($row[4]) : '';
                    $terpenuhi = in_array(strtolower($statusPerpustakaan), ['ya', 'aktif', 'true', '1', 'terpenuhi']);
                    
                    Perpustakaan::create([
                        'siswa_id' => $siswa->id,
                        'terpenuhi' => $terpenuhi,
                        'keterangan' => $terpenuhi ? 'Import data - Terpenuhi' : 'Import data - Belum terpenuhi',
                        'tanggal_pemenuhan' => $terpenuhi ? now() : null
                    ]);
                    
                    $importedCount++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                }
            }
            
            DB::commit();
            
            $this->importProgress = 90;
            $this->importStatus = 'Menyelesaikan import...';

            // Hapus file sementara
            Storage::delete($path);

            $this->importProgress = 100;
            
            if (!empty($errors)) {
                $errorMessage = "Import selesai dengan beberapa error:\n" . implode("\n", array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $errorMessage .= "\n... dan " . (count($errors) - 5) . " error lainnya";
                }
                $this->importStatus = "Import selesai: {$importedCount} data berhasil, " . count($errors) . " error";
                $this->dispatch('import-warning', $errorMessage);
            } else {
                $this->importStatus = "Import berhasil: {$importedCount} data diimport ke kelas {$kelas->nama_kelas}!";
                $this->dispatch('import-success', "Data berhasil diimport! Total: {$importedCount} siswa ke kelas {$kelas->nama_kelas}");
            }
            
            // Dispatch event to close loading
            $this->dispatch('import-completed');
            $this->isImporting = false;

        } catch (\Exception $e) {
            DB::rollBack();
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
        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set headers
            $headers = [
                'A1' => 'Nama Siswa',
                'B1' => 'Jenis Kelamin (L/P)',
                'C1' => 'NISN',
                'D1' => 'NIS',
                'E1' => 'Status Perpustakaan (Ya/Tidak)'
            ];
            
            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
                $sheet->getStyle($cell)->getFont()->setBold(true);
                $sheet->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE2E2E2');
            }
            
            // Add sample data
            $sampleData = [
                ['Ahmad Rizki', 'L', '1234567890', '001', 'Ya'],
                ['Siti Nurhaliza', 'P', '1234567891', '002', 'Tidak'],
                ['Muhammad Fadli', 'L', '1234567892', '003', 'Ya']
            ];
            
            $row = 2;
            foreach ($sampleData as $data) {
                $col = 'A';
                foreach ($data as $value) {
                    $sheet->setCellValue($col . $row, $value);
                    $col++;
                }
                $row++;
            }
            
            // Auto-size columns
            foreach (range('A', 'E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Create writer and download
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'template_import_siswa_kelas_' . date('Y-m-d_H-i-s') . '.xlsx';
            $tempFile = tempnam(sys_get_temp_dir(), $filename);
            
            $writer->save($tempFile);
            
            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            $this->dispatch('template-error', 'Gagal mengunduh template: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Get classes from active academic year
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        $kelasOptions = collect();
        
        if ($activeTahunPelajaran) {
            $kelasOptions = Kelas::with('guru')
                ->where('tahun_pelajaran_id', $activeTahunPelajaran->id)
                ->orderBy('nama_kelas')
                ->get();
        }
        
        // Get statistics for selected class
        $statistics = [];
        if ($this->kelas_id) {
            $kelas = Kelas::find($this->kelas_id);
            if ($kelas) {
                $statistics = [
                    'siswa_di_kelas' => KelasSiswa::where('kelas_id', $this->kelas_id)
                        ->where('tahun_pelajaran_id', $kelas->tahun_pelajaran_id)
                        ->count(),
                    'siswa_perpustakaan_aktif' => KelasSiswa::where('kelas_id', $this->kelas_id)
                        ->where('tahun_pelajaran_id', $kelas->tahun_pelajaran_id)
                        ->whereHas('siswa.perpustakaan', function($q) {
                            $q->where('terpenuhi', true);
                        })->count(),
                    'nama_kelas' => $kelas->nama_kelas,
                    'tingkat' => $kelas->tingkat,
                    'wali_kelas' => $kelas->guru ? $kelas->guru->nama_guru : 'Belum ditentukan'
                ];
            }
        }

        return view('livewire.import-management', [
            'kelasOptions' => $kelasOptions,
            'statistics' => $statistics,
            'activeTahunPelajaran' => $activeTahunPelajaran
        ])->layout('layouts.app', [
            'title' => 'Import Data Siswa per Kelas',
            'page-title' => 'Import Data Siswa per Kelas'
        ]);
    }
}