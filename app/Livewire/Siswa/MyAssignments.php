<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tugas;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyAssignments extends Component
{
    use WithPagination;

    public $search = '';
    public $filterTahunPelajaran = '';
    public $filterMataPelajaran = '';
    public $filterStatus = '';
    public $tahunPelajaranOptions = [];
    public $mataPelajaranOptions = [];
    public $statusOptions = [
        'pending' => 'Belum Dikerjakan',
        'submitted' => 'Sudah Dikumpulkan',
        'overdue' => 'Terlambat'
    ];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->tahunPelajaranOptions = TahunPelajaran::orderBy('nama_tahun_pelajaran', 'desc')->get();
        $this->mataPelajaranOptions = MataPelajaran::orderBy('nama_mapel')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterTahunPelajaran()
    {
        $this->resetPage();
    }

    public function updatingFilterMataPelajaran()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function getAssignmentStatus($tugas)
    {
        $now = Carbon::now();
        $deadline = Carbon::parse($tugas->deadline);
        
        // For now, we'll simulate assignment status since there's no submission table
        // In a real application, you'd check against a submissions table
        if ($deadline->isPast()) {
            return 'overdue';
        }
        
        // Randomly assign status for demo purposes
        // In real app, check if student has submitted
        return rand(0, 1) ? 'submitted' : 'pending';
    }

    public function render()
    {
        // Get the current user's siswa record
        $user = Auth::user();
        $siswa = Siswa::where('email', $user->email)->first();
        
        $query = Tugas::with(['mataPelajaran', 'guru', 'kelas', 'tahunPelajaran'])
            ->when($siswa, function ($q) use ($siswa) {
                // Get assignments for classes the student is enrolled in
                return $q->whereHas('kelas.siswa', function ($query) use ($siswa) {
                    $query->where('siswa_id', $siswa->id);
                });
            })
            ->when($this->search, function ($q) {
                return $q->where('judul_tugas', 'like', '%' . $this->search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterTahunPelajaran, function ($q) {
                return $q->where('tahun_pelajaran_id', $this->filterTahunPelajaran);
            })
            ->when($this->filterMataPelajaran, function ($q) {
                return $q->where('mata_pelajaran_id', $this->filterMataPelajaran);
            })
            ->orderBy('deadline', 'asc');

        $tugas = $query->paginate(10);

        // Add status to each assignment
        foreach ($tugas as $assignment) {
            $assignment->status = $this->getAssignmentStatus($assignment);
        }

        // Filter by status if selected
        if ($this->filterStatus) {
            $tugas = $tugas->filter(function ($assignment) {
                return $assignment->status === $this->filterStatus;
            });
        }

        // Calculate assignment statistics
        $assignmentStats = [];
        if ($siswa) {
            $allAssignments = Tugas::whereHas('kelas.siswa', function ($query) use ($siswa) {
                $query->where('siswa_id', $siswa->id);
            })->get();
            
            $pending = 0;
            $submitted = 0;
            $overdue = 0;
            
            foreach ($allAssignments as $assignment) {
                $status = $this->getAssignmentStatus($assignment);
                switch ($status) {
                    case 'pending':
                        $pending++;
                        break;
                    case 'submitted':
                        $submitted++;
                        break;
                    case 'overdue':
                        $overdue++;
                        break;
                }
            }
            
            $assignmentStats = [
                'pending' => $pending,
                'submitted' => $submitted,
                'overdue' => $overdue,
                'total' => $allAssignments->count()
            ];
        }

        return view('livewire.siswa.my-assignments', [
            'tugas' => $tugas,
            'siswa' => $siswa,
            'assignmentStats' => $assignmentStats
        ])->layout('layouts.app');
    }
}