<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Perpustakaan;
use App\Models\TahunPelajaran;
use App\Models\KelasSiswa;
use Illuminate\Support\Facades\DB;

class ClassManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterKelas = '';
    public $filterPerpustakaan = '';
    public $filterTahunPelajaran = '';

    // Form properties untuk edit siswa
    public $editingSiswa = null;
    public $editForm = [
        'nama_siswa' => '',
        'jk' => '',
        'nisn' => '',
        'nis' => '',
        'kelas_id' => '',
        'tahun_pelajaran_id' => '',
        'perpustakaan_terpenuhi' => false
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
        'perpustakaan_terpenuhi' => false
    ];

    protected function rules()
    {
        $rules = [
            'editForm.nama_siswa' => 'required|string|max:255',
            'editForm.jk' => 'required|in:L,P',
            'editForm.nisn' => 'required|string|unique:siswa,nisn,' . $this->editingSiswa,
            'editForm.nis' => 'required|string|unique:siswa,nis,' . $this->editingSiswa,
            'editForm.kelas_id' => 'required|exists:kelas,id',
            'editForm.tahun_pelajaran_id' => 'required|exists:tahun_pelajarans,id'
        ];

        // Add create form rules
        $rules = array_merge($rules, [
            'createForm.nama_siswa' => 'required|string|max:255',
            'createForm.jk' => 'required|in:L,P',
            'createForm.nisn' => 'required|string|unique:siswa,nisn',
            'createForm.nis' => 'required|string|unique:siswa,nis',
            'createForm.kelas_id' => 'required|exists:kelas,id',
            'createForm.tahun_pelajaran_id' => 'required|exists:tahun_pelajarans,id'
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
        'createForm.tahun_pelajaran_id.exists' => 'Tahun pelajaran yang dipilih tidak valid.'
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



    public function editSiswa($siswaId)
    {
        $siswa = Siswa::with('perpustakaan')->find($siswaId);
        
        if ($siswa) {
            $this->editingSiswa = $siswaId;
            $this->editForm = [
                'nama_siswa' => $siswa->nama_siswa,
                'jk' => $siswa->jk,
                'nisn' => $siswa->nisn,
                'nis' => $siswa->nis,
                'kelas_id' => $siswa->current_kelas_id,
                'tahun_pelajaran_id' => $siswa->tahun_pelajaran_id,
                'perpustakaan_terpenuhi' => $siswa->perpustakaan ? $siswa->perpustakaan->terpenuhi : false
            ];
        }
    }

    public function updateSiswa()
    {
        try {
            $this->validate();

            DB::transaction(function () {
                $siswa = Siswa::find($this->editingSiswa);
                $siswa->update([
                    'nama_siswa' => $this->editForm['nama_siswa'],
                    'jk' => $this->editForm['jk'],
                    'nisn' => $this->editForm['nisn'],
                    'nis' => $this->editForm['nis'],
                    'tahun_pelajaran_id' => $this->editForm['tahun_pelajaran_id']
                ]);

                // Update KelasSiswa record for the active academic year
                $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
                if ($activeTahunPelajaran) {
                    $siswa->kelasSiswa()->updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'tahun_pelajaran_id' => $activeTahunPelajaran->id
                        ],
                        [
                            'kelas_id' => $this->editForm['kelas_id']
                        ]
                    );
                }

                // Update atau buat record perpustakaan
                $siswa->perpustakaan()->updateOrCreate(
                    ['siswa_id' => $siswa->id],
                    ['terpenuhi' => $this->editForm['perpustakaan_terpenuhi']]
                );
            });

            $this->dispatch('siswa-updated', 'Data siswa berhasil diupdate!');
            $this->cancelEdit();

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors specifically
            $errors = $e->validator->errors()->all();
            $this->dispatch('update-error', 'Validasi gagal: ' . implode(', ', $errors));
        } catch (\Exception $e) {
            $this->dispatch('update-error', $e->getMessage());
        }
    }

    // Method untuk validasi real-time field NISN
    public function updatedEditFormNisn()
    {
        try {
            $this->validateOnly('editForm.nisn');
            $this->dispatch('validation-success', 'editForm.nisn');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-error', 'editForm.nisn');
        }
    }

    // Method untuk validasi real-time field NIS
    public function updatedEditFormNis()
    {
        try {
            $this->validateOnly('editForm.nis');
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
            'perpustakaan_terpenuhi' => false
        ];
    }

    // Methods untuk create modal
    public function openCreateModal()
    {
        $this->showCreateModal = true;
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        $this->createForm = [
            'nama_siswa' => '',
            'jk' => '',
            'nisn' => '',
            'nis' => '',
            'kelas_id' => '',
            'tahun_pelajaran_id' => $activeTahunPelajaran ? $activeTahunPelajaran->id : '',
            'perpustakaan_terpenuhi' => false
        ];
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->createForm = [
            'nama_siswa' => '',
            'jk' => '',
            'nisn' => '',
            'nis' => '',
            'kelas_id' => '',
            'tahun_pelajaran_id' => '',
            'perpustakaan_terpenuhi' => false
        ];
        $this->resetErrorBag();
    }

    public function createSiswa()
    {
        try {
            $this->validate([
                'createForm.nama_siswa' => 'required|string|max:255',
                'createForm.jk' => 'required|in:L,P',
                'createForm.nisn' => 'required|string|unique:siswa,nisn',
                'createForm.nis' => 'required|string|unique:siswa,nis',
                'createForm.kelas_id' => 'required|exists:kelas,id',
                'createForm.tahun_pelajaran_id' => 'required|exists:tahun_pelajarans,id'
            ]);

            DB::transaction(function () {
                // Create new student
                $siswa = Siswa::create([
                    'nama_siswa' => $this->createForm['nama_siswa'],
                    'jk' => $this->createForm['jk'],
                    'nisn' => $this->createForm['nisn'],
                    'nis' => $this->createForm['nis'],
                    'tahun_pelajaran_id' => $this->createForm['tahun_pelajaran_id']
                ]);

                // Create KelasSiswa record
                KelasSiswa::create([
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $this->createForm['kelas_id'],
                    'tahun_pelajaran_id' => $this->createForm['tahun_pelajaran_id']
                ]);

                // Create Perpustakaan record
                Perpustakaan::create([
                    'siswa_id' => $siswa->id,
                    'terpenuhi' => $this->createForm['perpustakaan_terpenuhi']
                ]);
            });

            $this->dispatch('siswa-created', 'Data siswa berhasil ditambahkan!');
            $this->closeCreateModal();
            $this->resetPage();

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $this->dispatch('create-error', 'Validasi gagal: ' . implode(', ', $errors));
        } catch (\Exception $e) {
            $this->dispatch('create-error', $e->getMessage());
        }
    }

    // Method untuk validasi real-time field NISN pada create form
    public function updatedCreateFormNisn()
    {
        try {
            $this->validateOnly('createForm.nisn');
            $this->dispatch('validation-success', 'createForm.nisn');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-error', 'createForm.nisn');
        }
    }

    // Method untuk validasi real-time field NIS pada create form
    public function updatedCreateFormNis()
    {
        try {
            $this->validateOnly('createForm.nis');
            $this->dispatch('validation-success', 'createForm.nis');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-error', 'createForm.nis');
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
        $kelasPerTingkat = Kelas::with(['guru', 'siswa.perpustakaan'])
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

        return view('livewire.class-management', [
            'siswaList' => $siswaList,
            'kelasList' => $kelasList,
            'allKelasList' => $allKelasList,
            'kelasPerTingkat' => $kelasPerTingkat,
            'guruList' => $guruList,
            'tahunPelajaranOptions' => $tahunPelajaranOptions
        ])->layout('layouts.app', [
            'title' => 'Manajemen Kelas',
            'page-title' => 'Manajemen Kelas'
        ]);
    }
}
