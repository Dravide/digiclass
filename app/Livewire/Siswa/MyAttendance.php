<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyAttendance extends Component
{
    use WithPagination;

    public $filterTahunPelajaran = '';
    public $filterBulan = '';
    public $tahunPelajaranOptions = [];
    public $bulanOptions = [
        '01' => 'Januari',
        '02' => 'Februari', 
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->tahunPelajaranOptions = TahunPelajaran::orderBy('nama_tahun_pelajaran', 'desc')->get();
        $this->filterBulan = date('m'); // Default to current month
    }

    public function updatingFilterTahunPelajaran()
    {
        $this->resetPage();
    }

    public function updatingFilterBulan()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Get the current user's siswa record
        $user = Auth::user();
        $siswa = Siswa::where('email', $user->email)->first();
        
        $query = Presensi::with(['siswa', 'tahunPelajaran'])
            ->when($siswa, function ($q) use ($siswa) {
                return $q->where('siswa_id', $siswa->id);
            })
            ->when($this->filterTahunPelajaran, function ($q) {
                return $q->where('tahun_pelajaran_id', $this->filterTahunPelajaran);
            })
            ->when($this->filterBulan, function ($q) {
                return $q->whereMonth('tanggal', $this->filterBulan);
            })
            ->orderBy('tanggal', 'desc');

        $presensi = $query->paginate(15);

        // Calculate attendance statistics
        $attendanceStats = [];
        if ($siswa) {
            $totalQuery = Presensi::where('siswa_id', $siswa->id);
            
            if ($this->filterTahunPelajaran) {
                $totalQuery->where('tahun_pelajaran_id', $this->filterTahunPelajaran);
            }
            
            if ($this->filterBulan) {
                $totalQuery->whereMonth('tanggal', $this->filterBulan);
            }
            
            $attendanceStats = [
                'hadir' => (clone $totalQuery)->where('status', 'hadir')->count(),
                'izin' => (clone $totalQuery)->where('status', 'izin')->count(),
                'sakit' => (clone $totalQuery)->where('status', 'sakit')->count(),
                'alpha' => (clone $totalQuery)->where('status', 'alpha')->count(),
                'total' => $totalQuery->count()
            ];
            
            $attendanceStats['percentage'] = $attendanceStats['total'] > 0 
                ? round(($attendanceStats['hadir'] / $attendanceStats['total']) * 100, 1)
                : 0;
        }

        return view('livewire.siswa.my-attendance', [
            'presensi' => $presensi,
            'siswa' => $siswa,
            'attendanceStats' => $attendanceStats
        ])->layout('layouts.app');
    }
}