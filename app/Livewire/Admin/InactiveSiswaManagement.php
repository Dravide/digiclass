<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\DB;

class InactiveSiswaManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedKelas = '';
    public $selectedTahunPelajaran = '';
    public $selectedKeterangan = '';
    public $selectedSiswa;
    
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedKelas()
    {
        $this->resetPage();
    }

    public function updatingSelectedTahunPelajaran()
    {
        $this->resetPage();
    }

    public function updatingSelectedKeterangan()
    {
        $this->resetPage();
    }

    public function showDetail($siswaId)
    {
        $this->selectedSiswa = Siswa::with(['kelasSiswa.kelas', 'tahunPelajaran'])
            ->findOrFail($siswaId);
    }

    public function activateStudent($siswaId)
    {
        try {
            $siswa = Siswa::findOrFail($siswaId);
            $siswa->update([
                'status' => 'aktif',
                'keterangan' => null
            ]);
            
            session()->flash('message', 'Siswa berhasil diaktifkan kembali.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengaktifkan siswa: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Siswa::with(['kelasSiswa.kelas', 'tahunPelajaran'])
            ->where('status', '!=', 'aktif'); // Hanya siswa yang tidak aktif

        // Filter berdasarkan pencarian
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_siswa', 'like', '%' . $this->search . '%')
                  ->orWhere('nis', 'like', '%' . $this->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->search . '%');
            });
        }

        // Filter berdasarkan kelas
        if ($this->selectedKelas) {
            $query->whereHas('kelasSiswa.kelas', function ($q) {
                $q->where('id', $this->selectedKelas);
            });
        }

        // Filter berdasarkan tahun pelajaran
        if ($this->selectedTahunPelajaran) {
            $query->where('tahun_pelajaran_id', $this->selectedTahunPelajaran);
        }

        // Filter berdasarkan keterangan
        if ($this->selectedKeterangan) {
            $query->where('keterangan', $this->selectedKeterangan);
        }

        $siswaList = $query->orderBy('nama_siswa')->paginate(10);

        // Data untuk dropdown filter
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $tahunPelajaranList = TahunPelajaran::orderBy('tanggal_mulai', 'desc')->get();
        
        // Ambil semua keterangan unik dari siswa tidak aktif
        $keteranganList = Siswa::where('status', '!=', 'aktif')
            ->whereNotNull('keterangan')
            ->distinct()
            ->pluck('keterangan')
            ->filter()
            ->sort();

        return view('livewire.admin.inactive-siswa-management', [
            'siswaList' => $siswaList,
            'kelasList' => $kelasList,
            'tahunPelajaranList' => $tahunPelajaranList,
            'keteranganList' => $keteranganList
        ])->layout('layouts.app');
    }
}