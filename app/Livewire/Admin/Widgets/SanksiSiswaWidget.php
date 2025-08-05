<?php

namespace App\Livewire\Admin\Widgets;

use Livewire\Component;
use App\Models\Siswa;
use App\Models\PelanggaranSiswa;
use App\Models\SanksiPelanggaran;
use App\Models\TahunPelajaran;

class SanksiSiswaWidget extends Component
{
    public $tahunPelajaranId;
    public $totalSiswaPerluDitangani = 0;
    public $totalSiswaDalamProses = 0;
    public $totalSiswaSelesai = 0;
    public $siswaKritis = [];
    
    public function mount()
    {
        $this->tahunPelajaranId = TahunPelajaran::where('is_active', true)->first()?->id;
        $this->loadData();
    }
    
    public function loadData()
    {
        if (!$this->tahunPelajaranId) {
            return;
        }
        
        $siswaList = Siswa::with(['kelasSiswa.kelas'])
            ->where('tahun_pelajaran_id', $this->tahunPelajaranId)
            ->where('status', Siswa::STATUS_AKTIF)
            ->get();
            
        $siswaWithSanksi = $siswaList->map(function($siswa) {
            $currentKelas = $siswa->getCurrentKelas();
            if (!$currentKelas) {
                return null;
            }
            
            $tingkatKelas = $currentKelas->tingkat;
            $totalPoin = PelanggaranSiswa::getTotalPoinSiswa($siswa->id, $this->tahunPelajaranId);
            
            if ($totalPoin == 0) {
                return null;
            }
            
            $sanksi = SanksiPelanggaran::getSanksiByPoin($tingkatKelas, $totalPoin);
            
            if (!$sanksi) {
                return null;
            }
            
            $pelanggaranTerbaru = PelanggaranSiswa::where('siswa_id', $siswa->id)
                ->where('tahun_pelajaran_id', $this->tahunPelajaranId)
                ->orderBy('tanggal_pelanggaran', 'desc')
                ->first();
                
            $statusPenanganan = $pelanggaranTerbaru ? $pelanggaranTerbaru->status_penanganan : 'belum_ditangani';
            
            return (object) [
                'siswa' => $siswa,
                'kelas' => $currentKelas,
                'total_poin' => $totalPoin,
                'sanksi' => $sanksi,
                'status_penanganan' => $statusPenanganan
            ];
        })->filter()->values();
        
        // Hitung statistik
        $this->totalSiswaPerluDitangani = $siswaWithSanksi->where('status_penanganan', 'belum_ditangani')->count();
        $this->totalSiswaDalamProses = $siswaWithSanksi->where('status_penanganan', 'dalam_proses')->count();
        $this->totalSiswaSelesai = $siswaWithSanksi->where('status_penanganan', 'selesai')->count();
        
        // Ambil 5 siswa dengan poin tertinggi yang belum ditangani
        $this->siswaKritis = $siswaWithSanksi
            ->where('status_penanganan', 'belum_ditangani')
            ->sortByDesc('total_poin')
            ->take(5)
            ->values()
            ->toArray();
    }
    
    public function render()
    {
        return view('livewire.admin.widgets.sanksi-siswa-widget');
    }
}