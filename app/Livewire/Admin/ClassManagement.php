<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Perpustakaan;
use App\Models\TahunPelajaran;
use App\Models\KelasSiswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Carbon\Carbon;

class ClassManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterKelas = '';
    public $filterPerpustakaan = '';
    public $filterTahunPelajaran = '';
    public $filterStatus = '';
    public $filterKeterangan = '';

    // Form properties untuk edit siswa
    public $editingSiswa = null;
    public $editForm = [
        'nama_siswa' => '',
        'jk' => '',
        'nisn' => '',
        'nis' => '',
        'kelas_id' => '',
        'tahun_pelajaran_id' => '',
        'perpustakaan_terpenuhi' => false,
        'status' => '',
        'keterangan' => ''
    ];

    // Form properties untuk tambah siswa manual
    public $showCreateModal = false;
    public $createForm = [
        'nama_siswa' => '',
        'jk' => '',
        'nisn' => '',
        'nis' => '',
        'kelas_id' => '',
        'tahun_pelajaran_id' => '',
        'perpustakaan_terpenuhi' => false,
        'status' => '',
        'keterangan' => ''
    ];

    protected function rules()
    {
        $rules = [
            'editForm.nama_siswa' => 'required|string|max:255',
            'editForm.jk' => 'required|in:L,P',
            'editForm.nisn' => 'required|string|unique:siswa,nisn,' . $this->editingSiswa,
            'editForm.nis' => 'required|string|unique:siswa,nis,' . $this->editingSiswa,
            'editForm.kelas_id' => 'required|exists:kelas,id',
            'editForm.tahun_pelajaran_id' => 'required|exists:tahun_pelajarans,id',
            'editForm.status' => 'required|in:aktif,tidak_aktif',
            'editForm.keterangan' => 'required|in:siswa_baru,pindahan,mengundurkan_diri,keluar,meninggal_dunia,alumni'
        ];

        // Add create form rules
        $rules = array_merge($rules, [
            'createForm.nama_siswa' => 'required|string|max:255',
            'createForm.jk' => 'required|in:L,P',
            'createForm.nisn' => 'required|string|unique:siswa,nisn',
            'createForm.nis' => 'required|string|unique:siswa,nis',
            'createForm.kelas_id' => 'required|exists:kelas,id',
            'createForm.tahun_pelajaran_id' => 'required|exists:tahun_pelajarans,id',
            'createForm.status' => 'required|in:aktif,tidak_aktif',
            'createForm.keterangan' => 'required|in:siswa_baru,pindahan,mengundurkan_diri,keluar,meninggal_dunia,alumni'
        ]);

        return $rules;
    }

    protected $messages = [
        'editForm.nama_siswa.required' => 'Nama siswa wajib diisi.',
        'editForm.nama_siswa.max' => 'Nama siswa maksimal 255 karakter.',
        'editForm.jk.required' => 'Jenis kelamin wajib dipilih.',
        'editForm.jk.in' => 'Jenis kelamin harus L atau P.',
        'editForm.nisn.required' => 'NISN wajib diisi.',
        'editForm.nisn.unique' => 'NISN sudah digunakan oleh siswa lain.',
        'editForm.nis.required' => 'NIS wajib diisi.',
        'editForm.nis.unique' => 'NIS sudah digunakan oleh siswa lain.',
        'editForm.kelas_id.required' => 'Kelas wajib dipilih.',
        'editForm.kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
        'editForm.tahun_pelajaran_id.required' => 'Tahun pelajaran wajib dipilih.',
        'editForm.tahun_pelajaran_id.exists' => 'Tahun pelajaran yang dipilih tidak valid.',
        
        // Create form messages
        'createForm.nama_siswa.required' => 'Nama siswa wajib diisi.',
        'createForm.nama_siswa.max' => 'Nama siswa maksimal 255 karakter.',
        'createForm.jk.required' => 'Jenis kelamin wajib dipilih.',
        'createForm.jk.in' => 'Jenis kelamin harus L atau P.',
        'createForm.nisn.required' => 'NISN wajib diisi.',
        'createForm.nisn.unique' => 'NISN sudah digunakan oleh siswa lain.',
        'createForm.nis.required' => 'NIS wajib diisi.',
        'createForm.nis.unique' => 'NIS sudah digunakan oleh siswa lain.',
        'createForm.kelas_id.required' => 'Kelas wajib dipilih.',
        'createForm.kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
        'createForm.tahun_pelajaran_id.required' => 'Tahun pelajaran wajib dipilih.',
        'createForm.tahun_pelajaran_id.exists' => 'Tahun pelajaran yang dipilih tidak valid.',
        'createForm.status.required' => 'Status wajib dipilih.',
        'createForm.status.in' => 'Status harus aktif atau tidak aktif.',
        'createForm.keterangan.required' => 'Keterangan wajib dipilih.',
        'createForm.keterangan.in' => 'Keterangan yang dipilih tidak valid.',
        
        // Edit form status and keterangan messages
        'editForm.status.required' => 'Status wajib dipilih.',
        'editForm.status.in' => 'Status harus aktif atau tidak aktif.',
        'editForm.keterangan.required' => 'Keterangan wajib dipilih.',
        'editForm.keterangan.in' => 'Keterangan yang dipilih tidak valid.'
    ];

    public function mount()
    {
        // Initialize filter with active academic year
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        $this->filterTahunPelajaran = $activeTahunPelajaran ? $activeTahunPelajaran->id : '';
        
        // Initialize create form with active academic year
        $this->createForm['tahun_pelajaran_id'] = $activeTahunPelajaran ? $activeTahunPelajaran->id : '';
        
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterKelas()
    {
        $this->resetPage();
    }

    public function updatingFilterPerpustakaan()
    {
        $this->resetPage();
    }

    public function updatingFilterTahunPelajaran()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterKeterangan()
    {
        $this->resetPage();
    }



    public function editSiswa($siswaId)
    {
        $siswa = Siswa::with(['perpustakaan', 'kelasSiswa'])->find($siswaId);
        
        if ($siswa) {
            $this->editingSiswa = $siswaId;
            
            // Get current kelas_id from KelasSiswa record for the student's tahun_pelajaran_id
            $currentKelasSiswa = $siswa->kelasSiswa
                ->where('tahun_pelajaran_id', $siswa->tahun_pelajaran_id)
                ->first();
            $currentKelasId = $currentKelasSiswa ? $currentKelasSiswa->kelas_id : null;
            
            $this->editForm = [
                'nama_siswa' => $siswa->nama_siswa,
                'jk' => $siswa->jk,
                'nisn' => $siswa->nisn,
                'nis' => $siswa->nis,
                'kelas_id' => $currentKelasId,
                'tahun_pelajaran_id' => $siswa->tahun_pelajaran_id,
                'status' => $siswa->status,
                'keterangan' => $siswa->keterangan
            ];
            
            // Reset validation errors
            $this->resetErrorBag();
            $this->resetValidation();
        }
    }

    public function updateSiswa()
    {
        try {
            // Validate only the edit form fields
            $validatedData = $this->validate([
                'editForm.nama_siswa' => 'required|string|max:255',
                'editForm.jk' => 'required|in:L,P',
                'editForm.nisn' => 'required|string|unique:siswa,nisn,' . $this->editingSiswa,
                'editForm.nis' => 'required|string|unique:siswa,nis,' . $this->editingSiswa,
                'editForm.kelas_id' => 'required|exists:kelas,id',
                'editForm.tahun_pelajaran_id' => 'required|exists:tahun_pelajarans,id',
                'editForm.status' => 'required|in:aktif,tidak_aktif',
                'editForm.keterangan' => 'required|in:siswa_baru,pindahan,mengundurkan_diri,keluar,meninggal_dunia,alumni'
            ], [
                'editForm.nama_siswa.required' => 'Nama siswa wajib diisi.',
                'editForm.jk.required' => 'Jenis kelamin wajib dipilih.',
                'editForm.nisn.required' => 'NISN wajib diisi.',
                'editForm.nis.required' => 'NIS wajib diisi.',
                'editForm.kelas_id.required' => 'Kelas wajib dipilih.',
                'editForm.tahun_pelajaran_id.required' => 'Tahun pelajaran wajib dipilih.',
                'editForm.status.required' => 'Status wajib dipilih.',
                'editForm.keterangan.required' => 'Keterangan wajib dipilih.',
            ]);

            DB::transaction(function () {
                $siswa = Siswa::find($this->editingSiswa);
                
                if (!$siswa) {
                    throw new \Exception('Data siswa tidak ditemukan.');
                }
                
                // Update siswa data
                $siswa->update([
                    'nama_siswa' => $this->editForm['nama_siswa'],
                    'jk' => $this->editForm['jk'],
                    'nisn' => $this->editForm['nisn'],
                    'nis' => $this->editForm['nis'],
                    'tahun_pelajaran_id' => $this->editForm['tahun_pelajaran_id'],
                    'status' => $this->editForm['status'],
                    'keterangan' => $this->editForm['keterangan']
                ]);

                // Update KelasSiswa record for the selected academic year
                if ($this->editForm['kelas_id']) {
                    $siswa->kelasSiswa()->updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'tahun_pelajaran_id' => $this->editForm['tahun_pelajaran_id']
                        ],
                        [
                            'kelas_id' => $this->editForm['kelas_id']
                        ]
                    );
                }
            });

            $this->dispatch('siswa-updated', 'Data siswa berhasil diperbarui!');
            $this->cancelEdit();
            $this->resetPage(); // Refresh the page data
            $this->dispatch('close-edit-modal'); // Close the modal

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors specifically
            $errors = $e->validator->errors()->all();
            $this->dispatch('update-error', 'Validasi gagal: ' . implode(', ', $errors));
        } catch (\Exception $e) {
            $this->dispatch('update-error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk validasi real-time field NISN
    public function updatedEditFormNisn()
    {
        try {
            $this->validate([
                'editForm.nisn' => 'required|string|unique:siswa,nisn,' . $this->editingSiswa
            ]);
            $this->dispatch('validation-success', 'editForm.nisn');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-error', 'editForm.nisn');
        }
    }

    // Method untuk validasi real-time field NIS
    public function updatedEditFormNis()
    {
        try {
            $this->validate([
                'editForm.nis' => 'required|string|unique:siswa,nis,' . $this->editingSiswa
            ]);
            $this->dispatch('validation-success', 'editForm.nis');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-error', 'editForm.nis');
        }
    }

    public function cancelEdit()
    {
        $this->editingSiswa = null;
        $this->editForm = [
            'nama_siswa' => '',
            'jk' => '',
            'nisn' => '',
            'nis' => '',
            'kelas_id' => '',
            'tahun_pelajaran_id' => '',
            'perpustakaan_terpenuhi' => false,
            'status' => '',
            'keterangan' => ''
        ];
        
        // Reset validation errors
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // === STUDENT CREATION - SIMPLIFIED APPROACH ===
    
    public function openCreateModal()
    {
        $this->showCreateModal = true;
        $this->resetCreateForm();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetCreateForm();
    }

    public function resetCreateForm()
    {
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        $this->createForm = [
            'nama_siswa' => '',
            'jk' => '',
            'nisn' => '',
            'nis' => '',
            'kelas_id' => '',
            'tahun_pelajaran_id' => $activeTahunPelajaran ? $activeTahunPelajaran->id : '',
            'perpustakaan_terpenuhi' => false,
            'status' => 'aktif',
            'keterangan' => 'siswa_baru'
        ];
        
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function createSiswa()
    {
        // Simple validation rules
        $validatedData = $this->validate([
            'createForm.nama_siswa' => 'required|string|max:255',
            'createForm.jk' => 'required|in:L,P',
            'createForm.nisn' => 'required|string|unique:siswa,nisn',
            'createForm.nis' => 'required|string|unique:siswa,nis',
            'createForm.kelas_id' => 'required|exists:kelas,id',
            'createForm.tahun_pelajaran_id' => 'required|exists:tahun_pelajarans,id',
            'createForm.status' => 'required|in:aktif,tidak_aktif',
            'createForm.keterangan' => 'required|in:siswa_baru,pindahan,mengundurkan_diri,keluar,meninggal_dunia,alumni'
        ]);

        try {
            DB::transaction(function () {
                // Create student
                $siswa = Siswa::create([
                    'nama_siswa' => $this->createForm['nama_siswa'],
                    'jk' => $this->createForm['jk'],
                    'nisn' => $this->createForm['nisn'],
                    'nis' => $this->createForm['nis'],
                    'tahun_pelajaran_id' => $this->createForm['tahun_pelajaran_id'],
                    'status' => $this->createForm['status'],
                    'keterangan' => $this->createForm['keterangan']
                ]);

                // Create class assignment
                KelasSiswa::create([
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $this->createForm['kelas_id'],
                    'tahun_pelajaran_id' => $this->createForm['tahun_pelajaran_id']
                ]);

                // Create library record
                Perpustakaan::create([
                    'siswa_id' => $siswa->id,
                    'terpenuhi' => $this->createForm['perpustakaan_terpenuhi']
                ]);

                // Create user account automatically
                // Generate email from NIS
                $email = $this->createForm['nis'] . '@siswa.digiclass.com';
                
                $user = User::create([
                    'name' => $this->createForm['nama_siswa'],
                    'email' => $email,
                    'password' => Hash::make('password123'), // Default password
                    'email_verified_at' => now(),
                ]);

                // Assign siswa role
                $siswaRole = Role::where('name', 'siswa')->first();
                if ($siswaRole) {
                    $user->assignRole($siswaRole);
                }

                // Update siswa record with email
                $siswa->update(['email' => $email]);
            });

            // Success notification
            $this->dispatch('siswa-created', 'Data siswa dan akun user berhasil ditambahkan! Email: ' . $this->createForm['nis'] . '@siswa.digiclass.com, Password: password123');
            $this->closeCreateModal();
            $this->resetPage();

        } catch (\Exception $e) {
            $this->dispatch('create-error', 'Gagal menambahkan data siswa: ' . $e->getMessage());
        }
    }



    public function deleteSiswa($siswaId)
    {
        try {
            $siswa = Siswa::find($siswaId);
            if ($siswa) {
                $siswa->delete();
                $this->dispatch('siswa-deleted', 'Data siswa berhasil dihapus!');
            }
        } catch (\Exception $e) {
            $this->dispatch('delete-error', $e->getMessage());
        }
    }

    protected $listeners = ['deleteSiswa'];







    public function render()
    {
        // Filter by academic year - default to active if no filter set
        $tahunPelajaranFilter = $this->filterTahunPelajaran;
        if (empty($tahunPelajaranFilter)) {
            $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
            $tahunPelajaranFilter = $activeTahunPelajaran ? $activeTahunPelajaran->id : null;
        }

        $query = Siswa::with(['perpustakaan', 'tahunPelajaran', 'kelasSiswa.kelas.guru'])
            ->when($tahunPelajaranFilter, function ($q) use ($tahunPelajaranFilter) {
                $q->where('tahun_pelajaran_id', $tahunPelajaranFilter);
            })
            ->when($this->search, function ($q) {
                $q->where('nama_siswa', 'like', '%' . $this->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->search . '%')
                  ->orWhere('nis', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterKelas, function ($q) {
                $q->whereHas('kelasSiswa', function ($subQ) {
                    $subQ->where('kelas_id', $this->filterKelas)
                        ->whereHas('tahunPelajaran', function ($tahunQ) {
                            $tahunQ->where('is_active', true);
                        });
                });
            })
            ->when($this->filterPerpustakaan !== '', function ($q) {
                if ($this->filterPerpustakaan === '1') {
                    $q->whereHas('perpustakaan', function ($subQ) {
                        $subQ->where('terpenuhi', true);
                    });
                } else {
                    $q->whereDoesntHave('perpustakaan')
                      ->orWhereHas('perpustakaan', function ($subQ) {
                          $subQ->where('terpenuhi', false);
                      });
                }
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            }, function ($q) {
                // Default: hanya tampilkan siswa dengan status aktif
                $q->where('status', 'aktif');
            })
            ->when($this->filterKeterangan, function ($q) {
                $q->where('keterangan', $this->filterKeterangan);
            });

        $siswaList = $query->paginate(10);
        
        // Filter kelas based on selected academic year for table filter
        $kelasList = Kelas::when($tahunPelajaranFilter, function ($q) use ($tahunPelajaranFilter) {
                $q->where('tahun_pelajaran_id', $tahunPelajaranFilter);
            })
            ->orderBy('nama_kelas')->get();
            
        // Get all available classes for create/edit forms (not filtered by academic year)
        $allKelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
            
        // Get classes grouped by grade level (7, 8, 9)
        $kelasPerTingkat = Kelas::with(['guru', 'siswa' => function ($query) {
                // Hanya tampilkan siswa dengan status aktif
                $query->where('status', 'aktif');
            }, 'siswa.perpustakaan'])
            ->when($tahunPelajaranFilter, function ($q) use ($tahunPelajaranFilter) {
                $q->where('tahun_pelajaran_id', $tahunPelajaranFilter);
            })
            ->whereIn('tingkat', [7, 8, 9])
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get()
            ->map(function ($kelas) {
                $kelas->siswa_count = $kelas->siswa->count();
                $kelas->siswa_list = $kelas->siswa;
                return $kelas;
            })
            ->groupBy('tingkat');
            
        // Ensure we have data for all grade levels
        foreach ([7, 8, 9] as $tingkat) {
            if (!$kelasPerTingkat->has($tingkat)) {
                $kelasPerTingkat[$tingkat] = collect();
            }
        }
            
        $guruList = Guru::orderBy('nama_guru')->get();
        $tahunPelajaranOptions = TahunPelajaran::orderBy('nama_tahun_pelajaran')->get();
        $mataPelajaranList = \App\Models\MataPelajaran::active()->orderBy('nama_mapel')->get();

        return view('livewire.admin.class-management', [
            'siswaList' => $siswaList,
            'kelasList' => $kelasList,
            'allKelasList' => $allKelasList,
            'kelasPerTingkat' => $kelasPerTingkat,
            'guruList' => $guruList,
            'tahunPelajaranOptions' => $tahunPelajaranOptions,
            'mataPelajaranList' => $mataPelajaranList
        ])->layout('layouts.app', [
            'title' => 'Manajemen Kelas',
            'page-title' => 'Manajemen Kelas'
        ]);
    }
}
