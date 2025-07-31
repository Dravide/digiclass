<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\Auth;

class MyClasses extends Component
{
    use WithPagination;

    public $search = '';
    public $filterTahunPelajaran = '';
    public $tahunPelajaranOptions = [];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->tahunPelajaranOptions = TahunPelajaran::orderBy('nama_tahun_pelajaran', 'desc')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterTahunPelajaran()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Get the current user's guru record
        $user = Auth::user();
        $guru = Guru::where('email', $user->email)->first();
        
        $query = Kelas::with(['tahunPelajaran', 'guru', 'siswa'])
            ->when($guru, function ($q) use ($guru) {
                return $q->where('guru_id', $guru->id);
            })
            ->when($this->search, function ($q) {
                return $q->where('nama_kelas', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterTahunPelajaran, function ($q) {
                return $q->where('tahun_pelajaran_id', $this->filterTahunPelajaran);
            })
            ->orderBy('nama_kelas');

        $kelas = $query->paginate(10);

        return view('livewire.guru.my-classes', [
            'kelas' => $kelas,
            'guru' => $guru
        ])->layout('layouts.app');
    }
}