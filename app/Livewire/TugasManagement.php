<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tugas;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Nilai;
use App\Models\Siswa;
use Carbon\Carbon;

class TugasManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterKelas = '';
    public $filterMataPelajaran = '';
    public $filterJenis = '';
    public $filterStatus = '';

    // Form properties
    public $showModal = false;
    public $editMode = false;
    public $tugasId;
    public $judul;
    public $deskripsi;
    public $mata_pelajaran_id;
    public $kelas_id;
    public $guru_id;
    public $tanggal_pemberian;
    public $tanggal_deadline;
    public $jenis = 'tugas_harian';
    public $bobot = 100;
    public $status = 'draft';
    public $catatan;

    protected $rules = [
        'judul' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
        'kelas_id' => 'required|exists:kelas,id',
        'guru_id' => 'required|exists:gurus,id',
        'tanggal_pemberian' => 'required|date',
        'tanggal_deadline' => 'required|date|after_or_equal:tanggal_pemberian',
        'jenis' => 'required|in:tugas_harian,ulangan_harian,uts,uas,praktikum,project',
        'bobot' => 'required|integer|min:1|max:100',
        'status' => 'required|in:aktif,selesai,draft',
        'catatan' => 'nullable|string'
    ];

    public function mount()
    {
        $this->tanggal_pemberian = Carbon::today()->format('Y-m-d');
        $this->tanggal_deadline = Carbon::today()->addDays(7)->format('Y-m-d');
    }

    public function render()
    {
        $query = Tugas::with(['mataPelajaran', 'kelas', 'guru'])
            ->when($this->search, function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhereHas('mataPelajaran', function($q) {
                      $q->where('nama', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('kelas', function($q) {
                      $q->where('nama', 'like', '%' . $this->search . '%');
                  });
            })
            ->when($this->filterKelas, function($q) {
                $q->where('kelas_id', $this->filterKelas);
            })
            ->when($this->filterMataPelajaran, function($q) {
                $q->where('mata_pelajaran_id', $this->filterMataPelajaran);
            })
            ->when($this->filterJenis, function($q) {
                $q->where('jenis', $this->filterJenis);
            })
            ->when($this->filterStatus, function($q) {
                $q->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc');

        $tugas = $query->paginate(10);
        $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $guru = Guru::orderBy('nama_guru')->get();

        return view('livewire.tugas-management', [
            'tugas' => $tugas,
            'mataPelajaran' => $mataPelajaran,
            'kelas' => $kelas,
            'guru' => $guru
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $tugas = Tugas::findOrFail($id);
        $this->tugasId = $tugas->id;
        $this->judul = $tugas->judul;
        $this->deskripsi = $tugas->deskripsi;
        $this->mata_pelajaran_id = $tugas->mata_pelajaran_id;
        $this->kelas_id = $tugas->kelas_id;
        $this->guru_id = $tugas->guru_id;
        $this->tanggal_pemberian = $tugas->tanggal_pemberian->format('Y-m-d');
        $this->tanggal_deadline = $tugas->tanggal_deadline->format('Y-m-d');
        $this->jenis = $tugas->jenis;
        $this->bobot = $tugas->bobot;
        $this->status = $tugas->status;
        $this->catatan = $tugas->catatan;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'mata_pelajaran_id' => $this->mata_pelajaran_id,
            'kelas_id' => $this->kelas_id,
            'guru_id' => $this->guru_id,
            'tanggal_pemberian' => $this->tanggal_pemberian,
            'tanggal_deadline' => $this->tanggal_deadline,
            'jenis' => $this->jenis,
            'bobot' => $this->bobot,
            'status' => $this->status,
            'catatan' => $this->catatan
        ];

        if ($this->editMode) {
            $tugas = Tugas::findOrFail($this->tugasId);
            $tugas->update($data);
            session()->flash('success', 'Tugas berhasil diperbarui!');
        } else {
            $tugas = Tugas::create($data);
            
            // Buat record nilai untuk semua siswa di kelas
            $siswaIds = $tugas->kelas->kelasSiswa()->pluck('siswa_id');
            foreach ($siswaIds as $siswaId) {
                Nilai::create([
                    'tugas_id' => $tugas->id,
                    'siswa_id' => $siswaId,
                    'status_pengumpulan' => 'belum_mengumpulkan'
                ]);
            }
            
            session()->flash('success', 'Tugas berhasil ditambahkan!');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function delete($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();
        session()->flash('success', 'Tugas berhasil dihapus!');
    }

    public function resetForm()
    {
        $this->tugasId = null;
        $this->judul = '';
        $this->deskripsi = '';
        $this->mata_pelajaran_id = '';
        $this->kelas_id = '';
        $this->guru_id = '';
        $this->tanggal_pemberian = Carbon::today()->format('Y-m-d');
        $this->tanggal_deadline = Carbon::today()->addDays(7)->format('Y-m-d');
        $this->jenis = 'tugas_harian';
        $this->bobot = 100;
        $this->status = 'draft';
        $this->catatan = '';
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
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

    public function updatingFilterJenis()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
}