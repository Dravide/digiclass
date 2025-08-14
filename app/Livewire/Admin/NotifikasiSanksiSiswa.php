<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Siswa;
use App\Models\PelanggaranSiswa;
use App\Models\SanksiPelanggaran;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class NotifikasiSanksiSiswa extends Component
{
    use WithPagination;

    public $tahunPelajaranId;
    public $tingkatKelas = '';
    public $statusFilter = 'semua'; // 'semua', 'perlu_ditangani', 'sudah_ditangani'
    public $search = '';
    public $showModal = false;
    public $selectedSiswa = null;
    public $selectedSanksi = null;
    public $catatan = '';
    
    // Properties for manual PDF generation
    public $showManualModal = false;
    public $manualSiswaId = null;
    public $manualJenisSP = '1';
    public $manualNomorSurat = '';
    public $manualPelanggaran = [];
    public $manualSanksi = '';
    
    protected $paginationTheme = 'bootstrap';
    
    public function mount()
    {
        $this->tahunPelajaranId = TahunPelajaran::where('is_active', true)->first()?->id;
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingTingkatKelas()
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    
    public function getSiswaYangPerluDitangani()
    {
        if (!$this->tahunPelajaranId) {
            return collect();
        }
        
        $query = Siswa::with(['kelasSiswa.kelas'])
            ->where('tahun_pelajaran_id', $this->tahunPelajaranId)
            ->where('status', Siswa::STATUS_AKTIF);
            
        // Filter berdasarkan pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_siswa', 'like', '%' . $this->search . '%')
                  ->orWhere('nis', 'like', '%' . $this->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->search . '%');
            });
        }
        
        $siswaList = $query->get();
        
        $siswaWithSanksi = $siswaList->map(function($siswa) {
            // Dapatkan kelas siswa saat ini
            $currentKelas = $siswa->getCurrentKelas();
            if (!$currentKelas) {
                return null;
            }
            
            $tingkatKelas = $currentKelas->tingkat;
            
            // Filter berdasarkan tingkat kelas jika dipilih
            if ($this->tingkatKelas && $tingkatKelas != $this->tingkatKelas) {
                return null;
            }
            
            // Hitung total poin pelanggaran siswa
            $totalPoin = PelanggaranSiswa::getTotalPoinSiswa($siswa->id, $this->tahunPelajaranId);
            
            if ($totalPoin == 0) {
                return null;
            }
            
            // Konversi tingkat kelas ke tingkat pelanggaran
            $tingkatPelanggaran = $this->getTingkatPelanggaranByKelas($tingkatKelas);
            
            // Cari sanksi yang sesuai
            $sanksi = SanksiPelanggaran::getSanksiByPoin($tingkatPelanggaran, $totalPoin);
            
            if (!$sanksi) {
                return null;
            }
            
            // Cek apakah sudah ada penanganan
            $pelanggaranTerbaru = PelanggaranSiswa::where('siswa_id', $siswa->id)
                ->where('tahun_pelajaran_id', $this->tahunPelajaranId)
                ->orderBy('tanggal_pelanggaran', 'desc')
                ->first();
                
            $statusPenanganan = $pelanggaranTerbaru ? $pelanggaranTerbaru->status_penanganan : 'belum_ditangani';
            
            // Filter berdasarkan status
            if ($this->statusFilter == 'perlu_ditangani' && $statusPenanganan != 'belum_ditangani') {
                return null;
            }
            if ($this->statusFilter == 'sudah_ditangani' && $statusPenanganan == 'belum_ditangani') {
                return null;
            }
            
            return (object) [
                'siswa' => $siswa,
                'kelas' => $currentKelas,
                'total_poin' => $totalPoin,
                'sanksi' => $sanksi,
                'status_penanganan' => $statusPenanganan,
                'pelanggaran_terbaru' => $pelanggaranTerbaru
            ];
        })->filter()->values();
        
        return $siswaWithSanksi;
    }
    
    public function showDetailSiswa($siswaId)
    {
        $siswa = Siswa::find($siswaId);
        if (!$siswa) {
            return;
        }
        
        $currentKelas = $siswa->getCurrentKelas();
        $totalPoin = PelanggaranSiswa::getTotalPoinSiswa($siswa->id, $this->tahunPelajaranId);
        $tingkatPelanggaran = $this->getTingkatPelanggaranByKelas($currentKelas->tingkat);
        $sanksi = SanksiPelanggaran::getSanksiByPoin($tingkatPelanggaran, $totalPoin);
        
        $this->selectedSiswa = $siswa;
        $this->selectedSanksi = $sanksi;
        $this->showModal = true;
        $this->catatan = '';
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedSiswa = null;
        $this->selectedSanksi = null;
        $this->catatan = '';
    }
    
    public function openManualModal()
    {
        $this->showManualModal = true;
        $this->resetManualForm();
    }
    
    public function closeManualModal()
    {
        $this->showManualModal = false;
        $this->resetManualForm();
    }
    
    private function resetManualForm()
    {
        $this->manualSiswaId = null;
        $this->manualJenisSP = '1';
        $this->manualNomorSurat = '';
        $this->manualPelanggaran = [];
        $this->manualSanksi = '';
    }
    
    public function addManualPelanggaran()
    {
        $this->manualPelanggaran[] = '';
    }
    
    public function removeManualPelanggaran($index)
    {
        unset($this->manualPelanggaran[$index]);
        $this->manualPelanggaran = array_values($this->manualPelanggaran);
    }
    
    public function updateStatusPenanganan($status)
    {
        if (!$this->selectedSiswa) {
            return;
        }
        
        // Update status pelanggaran terbaru siswa
        $pelanggaran = PelanggaranSiswa::where('siswa_id', $this->selectedSiswa->id)
            ->where('tahun_pelajaran_id', $this->tahunPelajaranId)
            ->orderBy('tanggal_pelanggaran', 'desc')
            ->first();
            
        if ($pelanggaran) {
            $pelanggaran->update([
                'status_penanganan' => $status,
                'catatan' => $this->catatan
            ]);
            
            session()->flash('message', 'Status penanganan berhasil diupdate!');
        }
        
        $this->closeModal();
    }
    
    // Generate PDF Otomatis (berdasarkan data pelanggaran siswa)
    public function generatePDF($siswaId, $jenisSP)
    {
        $siswa = Siswa::find($siswaId);
        if (!$siswa) {
            session()->flash('error', 'Siswa tidak ditemukan!');
            return;
        }
        
        $currentKelas = $siswa->getCurrentKelas();
        $totalPoin = PelanggaranSiswa::getTotalPoinSiswa($siswa->id, $this->tahunPelajaranId);
        
        // Ambil data pelanggaran siswa
        $pelanggaranList = PelanggaranSiswa::with(['jenisPelanggaran'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_pelajaran_id', $this->tahunPelajaranId)
            ->orderBy('tanggal_pelanggaran', 'desc')
            ->get();
            
        $tingkatPelanggaran = $this->getTingkatPelanggaranByKelas($currentKelas->tingkat);
        $sanksi = SanksiPelanggaran::getSanksiByPoin($tingkatPelanggaran, $totalPoin);
        
        // Data untuk PDF
        $data = [
            'siswa' => $siswa,
            'kelas' => $currentKelas,
            'totalPoin' => $totalPoin,
            'pelanggaranList' => $pelanggaranList,
            'sanksi' => $sanksi,
            'jenisSP' => $jenisSP,
            'tanggalSurat' => Carbon::now()->locale('id')->translatedFormat('d F Y'),
            'nomorSurat' => $this->generateNomorSurat($jenisSP),
            'isManual' => false
        ];
        
        $pdf = Pdf::loadView('exports.surat-peringatan', $data)
            ->setPaper('a4', 'portrait');
            
        $filename = "SP{$jenisSP}_{$siswa->nama_siswa}_{$currentKelas->nama_kelas}_" . date('Y-m-d') . ".pdf";
        
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }
    
    // Generate PDF Manual (input data manual)
    public function generateManualPDF()
    {
        $this->validate([
            'manualSiswaId' => 'required|exists:siswa,id',
            'manualJenisSP' => 'required|in:1,2,3',
            'manualNomorSurat' => 'required|string|max:100',
            'manualSanksi' => 'required|string|max:500'
        ]);
        
        $siswa = Siswa::find($this->manualSiswaId);
        $currentKelas = $siswa->getCurrentKelas();
        
        // Data untuk PDF Manual
        $data = [
            'siswa' => $siswa,
            'kelas' => $currentKelas,
            'totalPoin' => 0, // Manual tidak menggunakan poin otomatis
            'pelanggaranList' => collect(), // Manual tidak menggunakan pelanggaran otomatis
            'sanksi' => (object) ['deskripsi_sanksi' => $this->manualSanksi],
            'jenisSP' => $this->manualJenisSP,
            'tanggalSurat' => Carbon::now()->locale('id')->translatedFormat('d F Y'),
            'nomorSurat' => $this->manualNomorSurat,
            'isManual' => true,
            'manualPelanggaran' => $this->manualPelanggaran
        ];
        
        $pdf = Pdf::loadView('exports.surat-peringatan', $data)
            ->setPaper('a4', 'portrait');
            
        $filename = "SP{$this->manualJenisSP}_Manual_{$siswa->nama_siswa}_{$currentKelas->nama_kelas}_" . date('Y-m-d') . ".pdf";
        
        $this->closeManualModal();
        session()->flash('message', 'PDF berhasil dibuat!');
        
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }
    
    // Get list of students for dropdown
    public function getAllSiswa()
    {
        if (!$this->tahunPelajaranId) {
            return collect();
        }
        
        return Siswa::with(['kelasSiswa.kelas'])
            ->where('tahun_pelajaran_id', $this->tahunPelajaranId)
            ->where('status', Siswa::STATUS_AKTIF)
            ->orderBy('nama_siswa')
            ->get();
    }
    
    private function generateNomorSurat($jenisSP)
    {
        $tahun = date('Y');
        $bulan = date('m');
        $urutan = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        return "420/{$urutan}/SMPN1Cipanas/{$tahun}";
    }
    
    public function render()
    {
        $siswaData = $this->getSiswaYangPerluDitangani();
        
        // Pagination manual
        $perPage = 10;
        $currentPage = $this->getPage();
        $total = $siswaData->count();
        $items = $siswaData->slice(($currentPage - 1) * $perPage, $perPage);
        
        $tahunPelajarans = TahunPelajaran::orderBy('tanggal_mulai', 'desc')->get();
        $tingkatKelasList = [7, 8, 9];
        $allSiswa = $this->getAllSiswa();
        
        return view('livewire.admin.notifikasi-sanksi-siswa', [
            'siswaData' => $items,
            'tahunPelajarans' => $tahunPelajarans,
            'tingkatKelasList' => $tingkatKelasList,
            'allSiswa' => $allSiswa,
            'total' => $total,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ])->layout('layouts.app');
    }
    
    /**
     * Konversi tingkat kelas ke tingkat pelanggaran
     * Mapping berdasarkan kebijakan sekolah:
     * - Kelas 7-8: ringan
     * - Kelas 9-10: sedang  
     * - Kelas 11-12: berat
     */
    private function getTingkatPelanggaranByKelas($tingkatKelas)
    {
        if ($tingkatKelas <= 8) {
            return 'ringan';
        } elseif ($tingkatKelas <= 10) {
            return 'sedang';
        } else {
            return 'berat';
        }
    }
}