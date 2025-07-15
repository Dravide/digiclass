<?php

namespace App\Livewire;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Imports\GuruImport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class GuruManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Form properties
    public $nama_guru;
    public $nip;
    public $email;
    public $telepon;
    public $is_wali_kelas = false;
    public $mata_pelajaran_id;

    // State management
    public $isEditing = false;
    public $editingGuruId;

    // Search and filter
    public $search = '';
    public $perPage = 10;
    public $sortField = 'nama_guru';
    public $sortDirection = 'asc';
    public $filterWaliKelas = '';

    // Import properties
    public $importFile;
    public $showImportModal = false;
    public $importProgress = 0;
    public $importStatus = '';
    public $importErrors = [];
    public $importedCount = 0;

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'nama_guru' => 'required|string|max:100',
            'nip' => [
                'required',
                'string',
                'max:20',
                Rule::unique('gurus', 'nip')->ignore($this->editingGuruId)
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('gurus', 'email')->ignore($this->editingGuruId)
            ],
            'telepon' => 'required|string|max:15',
            'is_wali_kelas' => 'boolean',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
        ];
    }

    protected $messages = [
        'nama_guru.required' => 'Nama guru harus diisi.',
        'nama_guru.max' => 'Nama guru maksimal 100 karakter.',
        'nip.required' => 'NIP harus diisi.',
        'nip.unique' => 'NIP sudah terdaftar.',
        'nip.max' => 'NIP maksimal 20 karakter.',
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'email.max' => 'Email maksimal 100 karakter.',
        'telepon.required' => 'Telepon harus diisi.',
        'telepon.max' => 'Telepon maksimal 15 karakter.',
        'mata_pelajaran_id.exists' => 'Mata pelajaran yang dipilih tidak valid.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset([
            'nama_guru',
            'nip',
            'email',
            'telepon',
            'is_wali_kelas',
            'mata_pelajaran_id',
            'isEditing',
            'editingGuruId'
        ]);
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        try {
            Guru::create([
                'nama_guru' => $this->nama_guru,
                'nip' => $this->nip,
                'email' => $this->email,
                'telepon' => $this->telepon,
                'is_wali_kelas' => $this->is_wali_kelas,
                'mata_pelajaran_id' => $this->mata_pelajaran_id,
            ]);

            $this->resetForm();
            $this->dispatch('guru-created', 'Guru berhasil ditambahkan!');
        } catch (\Exception $e) {
            $this->dispatch('guru-error', 'Gagal menambahkan guru: ' . $e->getMessage());
        }
    }

    public function edit($guruId)
    {
        try {
            $guru = Guru::findOrFail($guruId);
            
            $this->editingGuruId = $guru->id;
            $this->nama_guru = $guru->nama_guru;
            $this->nip = $guru->nip;
            $this->email = $guru->email;
            $this->telepon = $guru->telepon;
            $this->is_wali_kelas = $guru->is_wali_kelas;
            $this->mata_pelajaran_id = $guru->mata_pelajaran_id;
            
            $this->isEditing = true;
            
            $this->resetValidation();
        } catch (\Exception $e) {
            $this->dispatch('guru-error', 'Gagal memuat data guru: ' . $e->getMessage());
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $guru = Guru::findOrFail($this->editingGuruId);
            
            $guru->update([
                'nama_guru' => $this->nama_guru,
                'nip' => $this->nip,
                'email' => $this->email,
                'telepon' => $this->telepon,
                'is_wali_kelas' => $this->is_wali_kelas,
                'mata_pelajaran_id' => $this->mata_pelajaran_id,
            ]);

            $this->resetForm();
            $this->dispatch('guru-updated', 'Guru berhasil diperbarui!');
        } catch (\Exception $e) {
            $this->dispatch('guru-error', 'Gagal memperbarui guru: ' . $e->getMessage());
        }
    }

    public function delete($guruId)
    {
        try {
            $guru = Guru::findOrFail($guruId);
            
            // Check if guru has associated siswa (if they are wali kelas)
            if ($guru->is_wali_kelas) {
                $siswaCount = \DB::table('kelas_siswa')
                    ->join('kelas', 'kelas.id', '=', 'kelas_siswa.kelas_id')
                    ->join('tahun_pelajarans', 'tahun_pelajarans.id', '=', 'kelas_siswa.tahun_pelajaran_id')
                    ->where('kelas.guru_id', $guru->id)
                    ->where('tahun_pelajarans.is_active', true)
                    ->count();
                    
                if ($siswaCount > 0) {
                    $this->dispatch('guru-error', 'Tidak dapat menghapus guru yang masih menjadi wali kelas dan memiliki siswa yang dibimbing!');
                    return;
                }
            }
            
            $guru->delete();
            
            $this->dispatch('guru-deleted', 'Guru berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('guru-error', 'Gagal menghapus guru: ' . $e->getMessage());
        }
    }

    public function openImportModal()
    {
        $this->showImportModal = true;
        $this->resetImportData();
    }

    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->resetImportData();
    }

    public function resetImportData()
    {
        $this->importFile = null;
        $this->importProgress = 0;
        $this->importStatus = '';
        $this->importErrors = [];
        $this->importedCount = 0;
    }

    public function importData()
    {
        $this->validate([
            'importFile' => 'required|mimes:xlsx,xls,csv|max:2048'
        ], [
            'importFile.required' => 'File import harus dipilih.',
            'importFile.mimes' => 'File harus berformat Excel (.xlsx, .xls) atau CSV.',
            'importFile.max' => 'Ukuran file maksimal 2MB.'
        ]);

        try {
            $this->importStatus = 'Memproses import...';
            $this->importProgress = 25;

            // Store the uploaded file temporarily
            $filePath = $this->importFile->store('temp-imports');
            
            $this->importProgress = 50;

            // Import the data
            $import = new GuruImport();
            Excel::import($import, $filePath);

            $this->importProgress = 75;

            // Clean up temporary file
            Storage::delete($filePath);

            $this->importProgress = 100;
            $this->importedCount = $import->getRowCount();
            $this->importStatus = "Import berhasil! {$this->importedCount} data guru telah diimport.";
            
            // Get any validation errors from import
            $this->importErrors = $import->getErrors();

            $this->dispatch('guru-imported', $this->importStatus);
            
            // Reset form and close modal after 2 seconds
            $this->dispatch('close-import-modal-delayed');
            
        } catch (\Exception $e) {
            $this->importStatus = 'Import gagal: ' . $e->getMessage();
            $this->importProgress = 0;
            
            // Clean up temporary file if it exists
            if (isset($filePath) && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
            
            $this->dispatch('guru-error', $this->importStatus);
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template_import_guru.xlsx"',
        ];

        // Create sample data for template
        $sampleData = [
            ['nama_guru', 'nip', 'email', 'telepon'],
            ['John Doe', 123456789012345678, 'john.doe@example.com', 81234567890],
            ['Jane Smith', 987654321098765432, 'jane.smith@example.com', 81234567891],
        ];

        return response()->streamDownload(function () use ($sampleData) {
            $file = fopen('php://output', 'w');
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, 'template_import_guru.csv', $headers);
    }

    public function render()
    {
        $query = Guru::with('mataPelajaran')
            ->when($this->search, function ($query) {
                $query->where('nama_guru', 'like', '%' . $this->search . '%')
                      ->orWhere('nip', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhereHas('mataPelajaran', function ($q) {
                          $q->where('nama_mapel', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filterWaliKelas !== '', function ($query) {
                $query->where('is_wali_kelas', $this->filterWaliKelas);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $gurus = $query->paginate($this->perPage);

        // Manually calculate siswa count for each guru
        foreach ($gurus as $guru) {
            if ($guru->is_wali_kelas) {
                // Count siswa through kelas_siswa for active academic year
                $siswaCount = \DB::table('kelas_siswa')
                    ->join('kelas', 'kelas.id', '=', 'kelas_siswa.kelas_id')
                    ->join('tahun_pelajarans', 'tahun_pelajarans.id', '=', 'kelas_siswa.tahun_pelajaran_id')
                    ->where('kelas.guru_id', $guru->id)
                    ->where('tahun_pelajarans.is_active', true)
                    ->count();
                $guru->siswa_count = $siswaCount;
            } else {
                $guru->siswa_count = 0;
            }
        }
        
        // Get active mata pelajaran for dropdown
        $mataPelajaranList = MataPelajaran::active()->orderBy('nama_mapel')->get();

        return view('livewire.guru-management', [
            'gurus' => $gurus,
            'mataPelajaranList' => $mataPelajaranList,
        ])->layout('layouts.app');
    }
}
