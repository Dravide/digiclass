<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use App\Models\KategoriPelanggaran;
use App\Models\JenisPelanggaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PelanggaranReport extends Component
{
    // Properties untuk autentikasi kode akses
    public $accessCode = '';
    public $isAuthenticated = false;
    public $showAccessForm = true;
    
    // Properties untuk form pelaporan
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
    
    // Properties untuk data
    public $tahunPelajaranAktif;
    public $kategoriPelanggarans;
    public $jenisPelanggarans = [];
    
    // Properties untuk search siswa
    public $siswaSearch = '';
    public $filteredSiswaList = [];
    public $selectedSiswaName = '';
    public $selectedSiswaDetails = [];
    
    // Kode akses yang valid (dalam implementasi nyata, ini harus disimpan di database atau config)
    private $validAccessCodes = [
        'GURU2025',
        'WALI2025',
        'BK2025',
        'ADMIN3035',
        'SISWA2025'
    ];
    
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
        $this->status_penanganan = 'belum_ditangani'; // Set default status
        
        $this->loadData();
    }
    
    public function loadData()
    {
        $this->kategoriPelanggarans = KategoriPelanggaran::with('jenisPelanggaran')->get();
    }
    
    public function verifyAccessCode()
    {
        $this->validate([
            'accessCode' => 'required|string|min:6'
        ], [
            'accessCode.required' => 'Kode akses harus diisi.',
            'accessCode.min' => 'Kode akses minimal 6 karakter.'
        ]);
        
        if (in_array(strtoupper($this->accessCode), $this->validAccessCodes)) {
            $this->isAuthenticated = true;
            $this->showAccessForm = false;
            session()->flash('success', 'Kode akses valid. Anda dapat melaporkan pelanggaran.');
        } else {
            session()->flash('error', 'Kode akses tidak valid. Silakan hubungi administrator.');
            $this->accessCode = '';
        }
    }
    
    public function logout()
    {
        $this->isAuthenticated = false;
        $this->showAccessForm = true;
        $this->accessCode = '';
        $this->resetForm();
        session()->flash('info', 'Anda telah keluar dari sistem pelaporan.');
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
    
    public function updatedSiswaSearch()
    {
        if (strlen($this->siswaSearch) >= 2) {
            $this->filteredSiswaList = Siswa::active()
                ->with(['kelasSiswa.kelas'])
                ->where(function($query) {
                    $query->where('nama_siswa', 'like', '%' . $this->siswaSearch . '%')
                          ->orWhere('nis', 'like', '%' . $this->siswaSearch . '%')
                          ->orWhere('nisn', 'like', '%' . $this->siswaSearch . '%');
                })
                ->orderBy('nama_siswa')
                ->limit(15)
                ->get()
                ->map(function($siswa) {
                    return [
                        'id' => $siswa->id,
                        'nama_siswa' => $siswa->nama_siswa,
                        'nis' => $siswa->nis,
                        'nisn' => $siswa->nisn,
                        'kelas' => $siswa->getCurrentKelas()?->nama_kelas ?? 'Tidak ada kelas'
                    ];
                })
                ->toArray();
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
            'nisn' => $siswaData['nisn'],
            'kelas' => $siswaData['kelas']
        ];
        $this->siswaSearch = $siswaData['nama_siswa'];
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
    
    public function savePelanggaran()
    {
        if (!$this->isAuthenticated) {
            session()->flash('error', 'Anda harus memasukkan kode akses terlebih dahulu.');
            return;
        }
        
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
        
        PelanggaranSiswa::create($data);
        
        session()->flash('success', 'Laporan pelanggaran berhasil disimpan. Terima kasih atas laporan Anda.');
        
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->siswa_id = null;
        $this->kategori_pelanggaran_id = null;
        $this->jenis_pelanggaran_id = null;
        $this->deskripsi_pelanggaran = '';
        $this->tanggal_pelanggaran = Carbon::today()->format('Y-m-d');
        $this->pelapor = '';
        $this->tindak_lanjut = '';
        $this->status_penanganan = 'belum_ditangani'; // Always reset to default
        $this->catatan = '';
        $this->jenisPelanggarans = [];
        
        // Reset search siswa properties
        $this->siswaSearch = '';
        $this->filteredSiswaList = [];
        $this->selectedSiswaName = '';
        $this->selectedSiswaDetails = [];
    }
    
    public function render()
    {
        return view('livewire.shared.pelanggaran-report')
            ->layout('layouts.main', ['title' => 'Laporan Pelanggaran Siswa - DigiClass']);
    }
}