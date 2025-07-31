<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\DB;

class RekapNilai extends Component
{
    use WithPagination;

    public $search = '';
    public $filterKelas = '';
    public $filterMataPelajaran = '';
    public $filterTahunPelajaran = '';
    public $selectedSiswa = null;
    public $showDetailModal = false;

    public function render()
    {
        // Get siswa with their average scores
        $query = Siswa::with(['kelasSiswa.kelas', 'nilai.tugas.mataPelajaran'])
            ->when($this->search, function($q) {
                $q->where('nama_siswa', 'like', '%' . $this->search . '%')
                  ->orWhere('nis', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterKelas, function($q) {
                $q->whereHas('kelasSiswa', function($q) {
                    $q->where('kelas_id', $this->filterKelas);
                });
            })
            ->when($this->filterMataPelajaran, function($q) {
                $q->whereHas('nilai.tugas', function($q) {
                    $q->where('mata_pelajaran_id', $this->filterMataPelajaran);
                });
            })
            ->when($this->filterTahunPelajaran, function($q) {
                $q->where('tahun_pelajaran_id', $this->filterTahunPelajaran);
            })
            ->orderBy('nama_siswa');

        try {
            $siswa = $query->paginate(15);
        } catch (\Exception $e) {
            $siswa = $query->simplePaginate(15);
            \Log::error('Rekap nilai pagination error: ' . $e->getMessage());
        }

        // Calculate statistics for each student
        foreach ($siswa as $s) {
            $nilaiQuery = $s->nilai();
            
            if ($this->filterMataPelajaran) {
                $nilaiQuery->whereHas('tugas', function($q) {
                    $q->where('mata_pelajaran_id', $this->filterMataPelajaran);
                });
            }
            
            $nilaiData = $nilaiQuery->whereNotNull('nilai')->get();
            
            $s->total_tugas = $nilaiData->count();
            $s->rata_rata = $nilaiData->avg('nilai') ?? 0;
            $s->nilai_tertinggi = $nilaiData->max('nilai') ?? 0;
            $s->nilai_terendah = $nilaiData->min('nilai') ?? 0;
            $s->jumlah_lulus = $nilaiData->where('nilai', '>=', 75)->count();
            $s->persentase_lulus = $s->total_tugas > 0 ? ($s->jumlah_lulus / $s->total_tugas) * 100 : 0;
        }

        $kelas = Kelas::orderBy('nama_kelas')->get();
        $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get();
        $tahunPelajaran = TahunPelajaran::orderBy('tanggal_mulai', 'desc')->get();

        return view('livewire.admin.rekap-nilai', [
            'siswa' => $siswa ?? collect(),
            'kelas' => $kelas ?? collect(),
            'mataPelajaran' => $mataPelajaran ?? collect(),
            'tahunPelajaran' => $tahunPelajaran ?? collect()
        ])->layout('layouts.app');
    }

    public function showDetail($siswaId)
    {
        try {
            $this->selectedSiswa = Siswa::with([
                'nilai.tugas.mataPelajaran',
                'nilai.tugas.kelas',
                'kelasSiswa.kelas'
            ])->find($siswaId);
            
            $this->showDetailModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memuat detail siswa.');
        }
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedSiswa = null;
    }



    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterKelas()
    {
        $this->resetPage();
    }

    public function updatingFilterMataPelajaran()
    {
        $this->resetPage();
    }

    public function updatingFilterTahunPelajaran()
    {
        $this->resetPage();
    }

    public function getGradeColor($nilai)
    {
        if ($nilai >= 90) return 'text-success';
        if ($nilai >= 80) return 'text-info';
        if ($nilai >= 75) return 'text-warning';
        if ($nilai >= 60) return 'text-primary';
        return 'text-danger';
    }

    public function getGradeLabel($nilai)
    {
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B+';
        if ($nilai >= 75) return 'B';
        if ($nilai >= 60) return 'C';
        return 'D';
    }

    public function exportExcel($siswaId = null)
    {
        try {
            if ($siswaId) {
                // Export untuk siswa tertentu
                session()->flash('info', 'Fitur export Excel untuk siswa individual akan segera tersedia.');
            } else {
                // Export untuk semua data
                session()->flash('info', 'Fitur export Excel untuk semua data akan segera tersedia.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal melakukan export Excel.');
        }
    }

    public function exportPDF($siswaId = null)
    {
        try {
            if ($siswaId) {
                // Export untuk siswa tertentu
                session()->flash('info', 'Fitur export PDF untuk siswa individual akan segera tersedia.');
            } else {
                // Export untuk semua data
                session()->flash('info', 'Fitur export PDF untuk semua data akan segera tersedia.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal melakukan export PDF.');
        }
    }
}