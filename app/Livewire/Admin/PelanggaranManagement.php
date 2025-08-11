<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use App\Models\KategoriPelanggaran;
use App\Models\JenisPelanggaran;
use App\Models\SanksiPelanggaran;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PelanggaranManagement extends Component
{
    use WithPagination;

    // Properties untuk form
    public $siswa_id;
    public $tahun_pelajaran_id;
    public $kategori_pelanggaran_id;
    public $jenis_pelanggaran_id;
    public $deskripsi_pelanggaran;
    public $tanggal_pelanggaran;
    public $pelapor;
    public $tindak_lanjut;
    public $status_penanganan = 'belum_ditangani';
    public $catatan;

    // Properties untuk filter dan pencarian
    public $search = '';
    public $filterKelas = '';
    public $filterStatus = '';
    public $filterTanggalMulai = '';
    public $filterTanggalSelesai = '';
    public $filterKategori = '';

    // Properties untuk modal dan state
    public $showModal = false;
    public $editMode = false;
    public $selectedPelanggaran = null;
    public $showDetailModal = false;
    public $selectedSiswa = null;

    // Properties untuk data
    public $tahunPelajaranAktif;
    public $kategoriPelanggarans;
    public $jenisPelanggarans = [];
    public $kelasList;
    
    // Properties untuk search siswa
    public $siswaSearch = '';
    public $filteredSiswaList = [];
    public $selectedSiswaName = '';
    public $selectedSiswaDetails = [];

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'siswa_id' => 'required|exists:siswa,id',
        'kategori_pelanggaran_id' => 'required|exists:kategori_pelanggaran,id',
        'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggaran,id',
        'deskripsi_pelanggaran' => 'required|string|max:1000',
        'tanggal_pelanggaran' => 'required|date',
        'pelapor' => 'required|string|max:255',
        'tindak_lanjut' => 'nullable|string|max:1000',
        'status_penanganan' => 'required|in:belum_ditangani,dalam_proses,selesai',
        'catatan' => 'nullable|string|max:1000'
    ];

    public function mount()
    {
        $this->tahunPelajaranAktif = TahunPelajaran::where('is_active', true)->first();
        $this->tahun_pelajaran_id = $this->tahunPelajaranAktif->id ?? null;
        $this->tanggal_pelanggaran = Carbon::today()->format('Y-m-d');
        $this->pelapor = Auth::user()->name ?? '';
        
        $this->loadData();
    }

    public function loadData()
    {
        $this->kategoriPelanggarans = KategoriPelanggaran::with('jenisPelanggaran')->get();
        $this->kelasList = Kelas::active()->with('tahunPelajaran')->get();
    }

    public function updatedFilterKategori()
    {
        if ($this->filterKategori) {
            $this->jenisPelanggarans = JenisPelanggaran::where('kategori_pelanggaran_id', $this->filterKategori)
                                                    ->active()
                                                    ->get();
        } else {
            $this->jenisPelanggarans = [];
        }
        $this->jenis_pelanggaran_id = null;
        $this->resetPage();
    }

    public function updatedFilterKelas()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterTanggalMulai()
    {
        $this->resetPage();
    }

    public function updatedFilterTanggalSelesai()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedKategoriPelanggaranId()
    {
        if ($this->kategori_pelanggaran_id) {
            $this->jenisPelanggarans = JenisPelanggaran::where('kategori_pelanggaran_id', $this->kategori_pelanggaran_id)
                                                    ->active()
                                                    ->get();
        } else {
            $this->jenisPelanggarans = [];
        }
        $this->jenis_pelanggaran_id = null;
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
        $this->dispatch('modalOpened');
    }

    public function editPelanggaran($id)
    {
        $pelanggaran = PelanggaranSiswa::findOrFail($id);
        
        $this->selectedPelanggaran = $pelanggaran;
        $this->siswa_id = $pelanggaran->siswa_id;
        $this->selectedSiswaName = $pelanggaran->siswa->nama_siswa;
        $this->selectedSiswaDetails = [
            'nis' => $pelanggaran->siswa->nis,
            'kelas' => $pelanggaran->siswa->getCurrentKelas()?->nama_kelas ?? 'Tidak ada kelas'
        ];
        $this->tahun_pelajaran_id = $pelanggaran->tahun_pelajaran_id;
        
        // Find the jenis pelanggaran and set kategori
        $jenisPelanggaran = JenisPelanggaran::where('nama_pelanggaran', $pelanggaran->jenis_pelanggaran)->first();
        if ($jenisPelanggaran) {
            $this->kategori_pelanggaran_id = $jenisPelanggaran->kategori_pelanggaran_id;
            $this->jenis_pelanggaran_id = $jenisPelanggaran->id;
            // Load jenis pelanggaran for this category
            $this->jenisPelanggarans = JenisPelanggaran::where('kategori_pelanggaran_id', $this->kategori_pelanggaran_id)
                                                    ->active()
                                                    ->get();
        }
        
        $this->deskripsi_pelanggaran = $pelanggaran->deskripsi_pelanggaran;
        $this->tanggal_pelanggaran = $pelanggaran->tanggal_pelanggaran->format('Y-m-d');
        $this->pelapor = $pelanggaran->pelapor;
        $this->tindak_lanjut = $pelanggaran->tindak_lanjut;
        $this->status_penanganan = $pelanggaran->status_penanganan;
        $this->catatan = $pelanggaran->catatan;
        
        $this->showModal = true;
        $this->editMode = true;
        $this->dispatch('modalOpened');
    }

    public function savePelanggaran()
    {
        $this->validate();

        // Cari jenis pelanggaran untuk mendapatkan poin
        $jenisPelanggaran = JenisPelanggaran::find($this->jenis_pelanggaran_id);
        
        $data = [
            'siswa_id' => $this->siswa_id,
            'tahun_pelajaran_id' => $this->tahun_pelajaran_id,
            'jenis_pelanggaran' => $jenisPelanggaran->nama_pelanggaran,
            'deskripsi_pelanggaran' => $this->deskripsi_pelanggaran,
            'poin_pelanggaran' => $jenisPelanggaran->poin_pelanggaran,
            'tanggal_pelanggaran' => $this->tanggal_pelanggaran,
            'pelapor' => $this->pelapor,
            'tindak_lanjut' => $this->tindak_lanjut,
            'status_penanganan' => $this->status_penanganan,
            'catatan' => $this->catatan
        ];

        if ($this->editMode) {
            $this->selectedPelanggaran->update($data);
            session()->flash('message', 'Data pelanggaran berhasil diperbarui.');
        } else {
            PelanggaranSiswa::create($data);
            session()->flash('message', 'Data pelanggaran berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function deletePelanggaran($id)
    {
        PelanggaranSiswa::findOrFail($id)->delete();
        session()->flash('message', 'Data pelanggaran berhasil dihapus.');
    }

    public function showDetail($siswaId)
    {
        $this->selectedSiswa = Siswa::with(['kelasSiswa.kelas', 'tahunPelajaran'])->find($siswaId);
        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->dispatch('modalClosed');
        $this->showModal = false;
        $this->showDetailModal = false;
        $this->resetForm();
    }
    
    public function updatedSiswaSearch()
    {
        if (strlen($this->siswaSearch) >= 2) {
            $this->filteredSiswaList = Siswa::active()
                ->with(['kelasSiswa.kelas'])
                ->where(function($query) {
                    $query->where('nama_siswa', 'like', '%' . $this->siswaSearch . '%')
                          ->orWhere('nis', 'like', '%' . $this->siswaSearch . '%');
                })
                ->orderBy('nama_siswa')
                ->limit(15)
                ->get();
                
            // Auto-select jika ada exact match
            $exactMatch = $this->filteredSiswaList->firstWhere('nama_siswa', $this->siswaSearch);
            if ($exactMatch) {
                $this->selectSiswaFromSearch($exactMatch);
            }
        } else {
            $this->filteredSiswaList = [];
        }
    }
    
    public function selectSiswaFromSearch($siswaData)
    {
        $this->siswa_id = $siswaData['id'];
        $this->selectedSiswaName = $siswaData['nama_siswa'];
        $this->selectedSiswaDetails = [
            'nis' => $siswaData['nis'],
            'kelas' => $siswaData['kelas']
        ];
        $this->siswaSearch = $siswaData['nama_siswa'];
        $this->filteredSiswaList = [];
    }
    
    public function clearSiswaSearch()
    {
        $this->siswaSearch = '';
        $this->filteredSiswaList = [];
    }
    
    public function selectSiswa($siswaId, $siswaName)
    {
        $this->siswa_id = $siswaId;
        $this->selectedSiswaName = $siswaName;
        $this->siswaSearch = '';
        $this->filteredSiswaList = [];
    }
    
    public function clearSiswaSelection()
    {
        $this->siswa_id = null;
        $this->selectedSiswaName = '';
        $this->selectedSiswaDetails = [];
        $this->siswaSearch = '';
        $this->filteredSiswaList = [];
    }

    public function resetForm()
    {
        $this->siswa_id = null;
        $this->kategori_pelanggaran_id = null;
        $this->jenis_pelanggaran_id = null;
        $this->deskripsi_pelanggaran = '';
        $this->tanggal_pelanggaran = Carbon::today()->format('Y-m-d');
        $this->pelapor = Auth::user()->name ?? '';
        $this->tindak_lanjut = '';
        $this->status_penanganan = 'belum_ditangani';
        $this->catatan = '';
        $this->selectedPelanggaran = null;
        $this->jenisPelanggarans = [];
        $this->filterKategori = '';
        
        // Reset search siswa properties
        $this->siswaSearch = '';
        $this->filteredSiswaList = [];
        $this->selectedSiswaName = '';
        $this->selectedSiswaDetails = [];
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterKelas = '';
        $this->filterStatus = '';
        $this->filterTanggalMulai = '';
        $this->filterTanggalSelesai = '';
        $this->filterKategori = '';
    }

    public function getTotalPoinSiswa($siswaId)
    {
        return PelanggaranSiswa::getTotalPoinSiswa($siswaId, $this->tahun_pelajaran_id);
    }

    public function getSanksiSiswa($siswaId)
    {
        $siswa = Siswa::find($siswaId);
        $kelas = $siswa->getCurrentKelas();
        $tingkatKelas = $kelas ? (int) substr($kelas->nama_kelas, 0, 1) : 7;
        
        $totalPoin = $this->getTotalPoinSiswa($siswaId);
        
        return SanksiPelanggaran::getSanksiByPoin($tingkatKelas, $totalPoin);
    }

    public function getRiwayatPelanggaranSiswa($siswaId)
    {
        return PelanggaranSiswa::where('siswa_id', $siswaId)
                              ->where('tahun_pelajaran_id', $this->tahun_pelajaran_id)
                              ->orderBy('tanggal_pelanggaran', 'desc')
                              ->get();
    }

    // Statistics methods for dashboard cards
    public function getTotalPelanggaran()
    {
        return PelanggaranSiswa::where('tahun_pelajaran_id', $this->tahun_pelajaran_id)->count();
    }

    public function getBelumDitangani()
    {
        return PelanggaranSiswa::where('tahun_pelajaran_id', $this->tahun_pelajaran_id)
                              ->where('status_penanganan', 'belum_ditangani')
                              ->count();
    }

    public function getDalamProses()
    {
        return PelanggaranSiswa::where('tahun_pelajaran_id', $this->tahun_pelajaran_id)
                              ->where('status_penanganan', 'dalam_proses')
                              ->count();
    }

    public function getSelesai()
    {
        return PelanggaranSiswa::where('tahun_pelajaran_id', $this->tahun_pelajaran_id)
                              ->where('status_penanganan', 'selesai')
                              ->count();
    }

    public function render()
    {
        $query = PelanggaranSiswa::with(['siswa.kelasSiswa.kelas', 'tahunPelajaran'])
                                ->where('tahun_pelajaran_id', $this->tahun_pelajaran_id);

        // Apply filters
        if ($this->search) {
            $query->whereHas('siswa', function ($q) {
                $q->where('nama_siswa', 'like', '%' . $this->search . '%')
                  ->orWhere('nis', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterKelas) {
            $query->whereHas('siswa.kelasSiswa', function ($q) {
                $q->where('kelas_id', $this->filterKelas)
                  ->whereHas('tahunPelajaran', function ($tq) {
                      $tq->where('is_active', true);
                  });
            });
        }

        if ($this->filterStatus) {
            $query->where('status_penanganan', $this->filterStatus);
        }

        if ($this->filterTanggalMulai) {
            $query->where('tanggal_pelanggaran', '>=', $this->filterTanggalMulai);
        }

        if ($this->filterTanggalSelesai) {
            $query->where('tanggal_pelanggaran', '<=', $this->filterTanggalSelesai);
        }

        // Get all pelanggarans and group by siswa
        $allPelanggarans = $query->orderBy('tanggal_pelanggaran', 'desc')->get();
        
        // Group pelanggarans by siswa_id
        $groupedPelanggarans = $allPelanggarans->groupBy('siswa_id')->map(function ($pelanggarans, $siswaId) {
            $siswa = $pelanggarans->first()->siswa;
            $totalPoin = $this->getTotalPoinSiswa($siswaId);
            $sanksi = $this->getSanksiSiswa($siswaId);
            
            return (object) [
                'siswa' => $siswa,
                'pelanggarans' => $pelanggarans,
                'total_pelanggaran' => $pelanggarans->count(),
                'total_poin' => $totalPoin,
                'sanksi' => $sanksi,
                'latest_pelanggaran' => $pelanggarans->first(),
                'status_counts' => [
                    'belum_ditangani' => $pelanggarans->where('status_penanganan', 'belum_ditangani')->count(),
                    'dalam_proses' => $pelanggarans->where('status_penanganan', 'dalam_proses')->count(),
                    'selesai' => $pelanggarans->where('status_penanganan', 'selesai')->count(),
                ]
            ];
        });

        // Convert to paginated collection using Livewire pagination
        $perPage = 10;
        $currentPage = $this->getPage();
        $currentItems = $groupedPelanggarans->slice(($currentPage - 1) * $perPage, $perPage);
        
        $paginatedSiswa = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $groupedPelanggarans->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page'
            ]
        );
        
        // Append query parameters to pagination links
        $paginatedSiswa->appends(request()->query());

        // Get siswa list for dropdown
        $siswaList = Siswa::active()
                          ->with(['kelasSiswa.kelas'])
                          ->orderBy('nama_siswa')
                          ->get();

        return view('livewire.admin.pelanggaran-management', [
            'groupedSiswa' => $paginatedSiswa,
            'siswaList' => $siswaList,
            'statusOptions' => PelanggaranSiswa::getAvailableStatuses(),
            'kategoriPelanggarans' => $this->kategoriPelanggarans,
            'kelasList' => $this->kelasList
        ])->layout('layouts.app');
    }
}