<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Auth;

class MyGrades extends Component
{
    use WithPagination;

    public $search = '';
    public $filterTahunPelajaran = '';
    public $filterMataPelajaran = '';
    public $tahunPelajaranOptions = [];
    public $mataPelajaranOptions = [];

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

    public function render()
    {
        // Get the current user's siswa record
        $user = Auth::user();
        $siswa = Siswa::where('email', $user->email)->first();
        
        $query = Nilai::with(['siswa', 'mataPelajaran', 'tahunPelajaran'])
            ->when($siswa, function ($q) use ($siswa) {
                return $q->where('siswa_id', $siswa->id);
            })
            ->when($this->search, function ($q) {
                return $q->whereHas('mataPelajaran', function ($query) {
                    $query->where('nama_mapel', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterTahunPelajaran, function ($q) {
                return $q->where('tahun_pelajaran_id', $this->filterTahunPelajaran);
            })
            ->when($this->filterMataPelajaran, function ($q) {
                return $q->where('mata_pelajaran_id', $this->filterMataPelajaran);
            })
            ->orderBy('created_at', 'desc');

        $nilai = $query->paginate(10);

        // Calculate average grades
        $averageGrades = [];
        if ($siswa) {
            $averageGrades = Nilai::where('siswa_id', $siswa->id)
                ->selectRaw('mata_pelajaran_id, AVG(nilai) as avg_nilai')
                ->groupBy('mata_pelajaran_id')
                ->with('mataPelajaran')
                ->get();
        }

        return view('livewire.siswa.my-grades', [
            'nilai' => $nilai,
            'siswa' => $siswa,
            'averageGrades' => $averageGrades
        ])->layout('layouts.app');
    }
}