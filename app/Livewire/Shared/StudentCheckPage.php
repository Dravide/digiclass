<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\Nilai;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\DB;

class StudentCheckPage extends Component
{
    public $nis = '';
    public $nisn = '';
    public $student = null;
    public $attendanceData = [];
    public $gradeData = [];
    public $showResults = false;
    public $errorMessage = '';
    public $selectedTahunPelajaran = null;
    public $tahunPelajaranList = [];

    public function mount()
    {
        $this->tahunPelajaranList = TahunPelajaran::orderBy('tanggal_mulai', 'desc')->get();
        $this->selectedTahunPelajaran = TahunPelajaran::where('is_active', true)->first()?->id;
    }

    public function checkStudent()
    {
        $this->validate([
            'nis' => 'required|string',
            'nisn' => 'required|string',
        ], [
            'nis.required' => 'NIS harus diisi',
            'nisn.required' => 'NISN harus diisi',
        ]);

        // Find student by NIS and NISN
        $this->student = Siswa::where('nis', $this->nis)
                             ->where('nisn', $this->nisn)
                             ->first();

        if (!$this->student) {
            $this->errorMessage = 'Data siswa tidak ditemukan. Pastikan NIS dan NISN yang dimasukkan benar.';
            $this->showResults = false;
            return;
        }

        $this->errorMessage = '';
        $this->loadAttendanceData();
        $this->loadGradeData();
        $this->showResults = true;
    }

    private function loadAttendanceData()
    {
        if (!$this->student || !$this->selectedTahunPelajaran) {
            return;
        }

        // Get attendance summary by month
        $attendanceQuery = Presensi::where('siswa_id', $this->student->id)
            ->whereHas('jadwal', function($query) {
                $query->whereHas('kelas', function($kelasQuery) {
                    $kelasQuery->where('tahun_pelajaran_id', $this->selectedTahunPelajaran);
                });
            })
            ->selectRaw('MONTH(tanggal) as month, YEAR(tanggal) as year, status, COUNT(*) as count')
            ->groupBy('month', 'year', 'status')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Group by month-year
        $grouped = $attendanceQuery->groupBy(function($item) {
            return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
        });

        $this->attendanceData = $grouped->map(function($monthData, $monthYear) {
            $hadir = $monthData->where('status', 'hadir')->sum('count');
            $sakit = $monthData->where('status', 'sakit')->sum('count');
            $izin = $monthData->where('status', 'izin')->sum('count');
            $alpha = $monthData->where('status', 'alpha')->sum('count');
            $total = $hadir + $sakit + $izin + $alpha;
            
            return [
                'month_year' => $monthYear,
                'month_name' => $this->getMonthName((int)substr($monthYear, 5, 2)),
                'year' => substr($monthYear, 0, 4),
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpha' => $alpha,
                'total' => $total,
                'percentage' => $total > 0 ? round(($hadir / $total) * 100, 1) : 0
            ];
        })->values()->toArray();
    }

    private function loadGradeData()
    {
        if (!$this->student || !$this->selectedTahunPelajaran) {
            return;
        }

        // Get grades grouped by subject
        $this->gradeData = Nilai::where('siswa_id', $this->student->id)
            ->whereHas('tugas.kelas', function($query) {
                $query->where('tahun_pelajaran_id', $this->selectedTahunPelajaran);
            })
            ->with(['tugas.mataPelajaran', 'tugas'])
            ->get()
            ->groupBy('tugas.mataPelajaran.nama_mapel')
            ->map(function($grades, $subject) {
                $totalNilai = $grades->sum('nilai');
                $count = $grades->count();
                $average = $count > 0 ? round($totalNilai / $count, 1) : 0;
                
                return [
                    'subject' => $subject,
                    'grades' => $grades->map(function($grade) {
                        return [
                            'tugas' => $grade->tugas->judul,
                            'nilai' => $grade->nilai,
                            'tanggal' => $grade->created_at->format('d/m/Y')
                        ];
                    })->toArray(),
                    'average' => $average,
                    'count' => $count
                ];
            })->toArray();
    }

    private function getMonthName($month)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $months[$month] ?? '';
    }

    public function updatedSelectedTahunPelajaran()
    {
        if ($this->student) {
            $this->loadAttendanceData();
            $this->loadGradeData();
        }
    }

    public function resetForm()
    {
        $this->nis = '';
        $this->nisn = '';
        $this->student = null;
        $this->attendanceData = [];
        $this->gradeData = [];
        $this->showResults = false;
        $this->errorMessage = '';
    }

    public function render()
    {
        return view('livewire.student-check-page')
            ->layout('layouts.public', ['title' => 'Cek Data Siswa - DigiClass']);
    }
}