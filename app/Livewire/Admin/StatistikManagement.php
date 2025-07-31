<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunPelajaran;
use App\Models\KelasSiswa;
use App\Models\Perpustakaan;
use Illuminate\Support\Facades\DB;

class StatistikManagement extends Component
{
    public $selectedTahunPelajaran;
    public $tahunPelajaranList;
    
    public function mount()
    {
        $this->tahunPelajaranList = TahunPelajaran::orderBy('is_active', 'desc')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();
        
        // Set default ke tahun pelajaran aktif
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        $this->selectedTahunPelajaran = $activeTahunPelajaran ? $activeTahunPelajaran->id : null;
    }
    
    public function getStatistikKeseluruhan()
    {
        $baseQuery = $this->selectedTahunPelajaran ? 
            KelasSiswa::where('tahun_pelajaran_id', $this->selectedTahunPelajaran) :
            KelasSiswa::query();
            
        return [
            'total_siswa' => (clone $baseQuery)->count(),
            'total_siswa_laki' => (clone $baseQuery)->whereHas('siswa', function($q) {
                $q->where('jk', 'L');
            })->count(),
            'total_siswa_perempuan' => (clone $baseQuery)->whereHas('siswa', function($q) {
                $q->where('jk', 'P');
            })->count(),
            'total_kelas' => $this->selectedTahunPelajaran ? 
                Kelas::where('tahun_pelajaran_id', $this->selectedTahunPelajaran)->count() :
                Kelas::count(),
            'total_guru' => Guru::count(),
            'total_perpustakaan_terpenuhi' => Perpustakaan::where('terpenuhi', true)->count(),
            'total_perpustakaan_belum_terpenuhi' => Perpustakaan::where('terpenuhi', false)->count(),
        ];
    }
    
    public function getStatistikPerTingkat()
    {
        $query = $this->selectedTahunPelajaran ? 
            Kelas::where('kelas.tahun_pelajaran_id', $this->selectedTahunPelajaran) :
            Kelas::query();
            
        return $query->select('tingkat')
            ->selectRaw('COUNT(DISTINCT kelas.id) as total_kelas')
            ->selectRaw('COUNT(kelas_siswa.id) as total_siswa')
            ->selectRaw('SUM(CASE WHEN siswa.jk = "L" THEN 1 ELSE 0 END) as total_laki')
            ->selectRaw('SUM(CASE WHEN siswa.jk = "P" THEN 1 ELSE 0 END) as total_perempuan')
            ->leftJoin('kelas_siswa', function($join) {
                $join->on('kelas.id', '=', 'kelas_siswa.kelas_id');
                if ($this->selectedTahunPelajaran) {
                    $join->where('kelas_siswa.tahun_pelajaran_id', $this->selectedTahunPelajaran);
                }
            })
            ->leftJoin('siswa', 'kelas_siswa.siswa_id', '=', 'siswa.id')
            ->groupBy('tingkat')
            ->orderBy('tingkat')
            ->get();
    }
    
    public function getStatistikPerKelas()
    {
        $query = $this->selectedTahunPelajaran ? 
            Kelas::where('kelas.tahun_pelajaran_id', $this->selectedTahunPelajaran) :
            Kelas::query();
            
        return $query->with(['guru', 'tahunPelajaran'])
            ->withCount([
                'kelasSiswa as total_siswa' => function($q) {
                    if ($this->selectedTahunPelajaran) {
                        $q->where('tahun_pelajaran_id', $this->selectedTahunPelajaran);
                    }
                },
                'kelasSiswa as total_laki' => function($q) {
                    $q->whereHas('siswa', function($siswaQuery) {
                        $siswaQuery->where('jk', 'L');
                    });
                    if ($this->selectedTahunPelajaran) {
                        $q->where('tahun_pelajaran_id', $this->selectedTahunPelajaran);
                    }
                },
                'kelasSiswa as total_perempuan' => function($q) {
                    $q->whereHas('siswa', function($siswaQuery) {
                        $siswaQuery->where('jk', 'P');
                    });
                    if ($this->selectedTahunPelajaran) {
                        $q->where('tahun_pelajaran_id', $this->selectedTahunPelajaran);
                    }
                }
            ])
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();
    }
    
    public function getStatistikStatus()
    {
        $query = $this->selectedTahunPelajaran ? 
            KelasSiswa::where('kelas_siswa.tahun_pelajaran_id', $this->selectedTahunPelajaran) :
            KelasSiswa::query();
            
        return $query->join('siswa', 'kelas_siswa.siswa_id', '=', 'siswa.id')
            ->select('siswa.status')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN siswa.jk = "L" THEN 1 ELSE 0 END) as total_laki')
            ->selectRaw('SUM(CASE WHEN siswa.jk = "P" THEN 1 ELSE 0 END) as total_perempuan')
            ->groupBy('siswa.status')
            ->get();
    }
    
    public function getStatistikKeterangan()
    {
        $query = $this->selectedTahunPelajaran ? 
            KelasSiswa::where('kelas_siswa.tahun_pelajaran_id', $this->selectedTahunPelajaran) :
            KelasSiswa::query();
            
        return $query->join('siswa', 'kelas_siswa.siswa_id', '=', 'siswa.id')
            ->select('siswa.keterangan')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN siswa.jk = "L" THEN 1 ELSE 0 END) as total_laki')
            ->selectRaw('SUM(CASE WHEN siswa.jk = "P" THEN 1 ELSE 0 END) as total_perempuan')
            ->groupBy('siswa.keterangan')
            ->get();
    }
    
    public function render()
    {
        $statistikKeseluruhan = $this->getStatistikKeseluruhan();
        $statistikPerTingkat = $this->getStatistikPerTingkat();
        $statistikPerKelas = $this->getStatistikPerKelas();
        $statistikStatus = $this->getStatistikStatus();
        $statistikKeterangan = $this->getStatistikKeterangan();
        
        return view('livewire.admin.statistik-management', compact(
            'statistikKeseluruhan',
            'statistikPerTingkat', 
            'statistikPerKelas',
            'statistikStatus',
            'statistikKeterangan'
        ))->layout('layouts.app',[
            'title' => 'Statistik',
            'subtitle' => 'Statistik Keseluruhan',
        ]);
    }
}