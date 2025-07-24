<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JurnalMengajar;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\TahunPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JurnalMengajarManagement extends Component
{
    use WithPagination;

    // Properties untuk form
    public $jurnalId;
    public $jadwal_id;
    public $guru_id;
    public $tanggal;
    public $jam_mulai;
    public $jam_selesai;
    public $materi_ajar;
    public $kegiatan_pembelajaran;
    public $metode_pembelajaran;
    public $jumlah_siswa_hadir = 0;
    public $jumlah_siswa_tidak_hadir = 0;
    public $kendala;
    public $solusi;
    public $catatan;
    public $status = 'draft';

    // Properties untuk UI
    public $showModal = false;
    public $editMode = false;
    public $viewMode = 'list'; // list, calendar, statistics
    public $perPage = 15;

    // Properties untuk filter
    public $search = '';
    public $filterGuru = '';
    public $filterKelas = '';
    public $filterMataPelajaran = '';
    public $filterStatus = '';
    public $filterTahunPelajaran = '';
    public $filterBulan = '';
    public $filterTahun = '';

    // Properties untuk data
    public $selectedJadwal;
    public $availableJadwal = [];

    protected $rules = [
        'jadwal_id' => 'required|exists:jadwal,id',
        'guru_id' => 'required|exists:gurus,id',
        'tanggal' => 'required|date',
        'jam_mulai' => 'required',
        'jam_selesai' => 'required',
        'materi_ajar' => 'required|string|max:255',
        'kegiatan_pembelajaran' => 'nullable|string',
        'metode_pembelajaran' => 'nullable|string',
        'jumlah_siswa_hadir' => 'required|integer|min:0',
        'jumlah_siswa_tidak_hadir' => 'required|integer|min:0',
        'kendala' => 'nullable|string',
        'solusi' => 'nullable|string',
        'catatan' => 'nullable|string'
    ];

    public function mount()
    {
        $this->filterTahun = date('Y');
        $this->filterTahunPelajaran = TahunPelajaran::where('is_active', true)->first()?->id ?? '';
    }

    public function render()
    {
        $query = JurnalMengajar::with(['jadwal.mataPelajaran', 'jadwal.kelas', 'jadwal.guru', 'guru', 'approvedBy'])
            ->when($this->search, function($q) {
                $q->where('materi_ajar', 'like', '%' . $this->search . '%')
                  ->orWhereHas('jadwal.mataPelajaran', function($q) {
                      $q->where('nama_mapel', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('jadwal.kelas', function($q) {
                      $q->where('nama_kelas', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('guru', function($q) {
                      $q->where('nama_guru', 'like', '%' . $this->search . '%');
                  });
            })
            ->when($this->filterGuru, function($q) {
                $q->where('guru_id', $this->filterGuru);
            })
            ->when($this->filterKelas, function($q) {
                $q->whereHas('jadwal', function($q) {
                    $q->where('kelas_id', $this->filterKelas);
                });
            })
            ->when($this->filterMataPelajaran, function($q) {
                $q->whereHas('jadwal', function($q) {
                    $q->where('mata_pelajaran_id', $this->filterMataPelajaran);
                });
            })
            ->when($this->filterStatus, function($q) {
                $q->where('status', $this->filterStatus);
            })
            ->when($this->filterTahunPelajaran, function($q) {
                $q->byTahunPelajaran($this->filterTahunPelajaran);
            })
            ->when($this->filterBulan, function($q) {
                $q->whereMonth('tanggal', $this->filterBulan);
            })
            ->when($this->filterTahun, function($q) {
                $q->whereYear('tanggal', $this->filterTahun);
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc');

        $jurnal = $query->paginate($this->perPage);

        // Data untuk dropdown
        $guru = Guru::orderBy('nama_guru')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get();
        $tahunPelajaran = TahunPelajaran::orderBy('tahun_mulai', 'desc')->get();

        // Statistics untuk dashboard
        $statistics = $this->getStatistics();

        return view('livewire.jurnal-mengajar-management', [
            'jurnal' => $jurnal,
            'guru' => $guru,
            'kelas' => $kelas,
            'mataPelajaran' => $mataPelajaran,
            'tahunPelajaran' => $tahunPelajaran,
            'statistics' => $statistics
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->loadAvailableJadwal();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $jurnal = JurnalMengajar::findOrFail($id);
        $this->jurnalId = $jurnal->id;
        $this->jadwal_id = $jurnal->jadwal_id;
        $this->guru_id = $jurnal->guru_id;
        $this->tanggal = $jurnal->tanggal->format('Y-m-d');
        $this->jam_mulai = $jurnal->jam_mulai->format('H:i');
        $this->jam_selesai = $jurnal->jam_selesai->format('H:i');
        $this->materi_ajar = $jurnal->materi_ajar;
        $this->kegiatan_pembelajaran = $jurnal->kegiatan_pembelajaran;
        $this->metode_pembelajaran = $jurnal->metode_pembelajaran;
        $this->jumlah_siswa_hadir = $jurnal->jumlah_siswa_hadir;
        $this->jumlah_siswa_tidak_hadir = $jurnal->jumlah_siswa_tidak_hadir;
        $this->kendala = $jurnal->kendala;
        $this->solusi = $jurnal->solusi;
        $this->catatan = $jurnal->catatan;
        $this->status = $jurnal->status;
        
        $this->editMode = true;
        $this->loadAvailableJadwal();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'jadwal_id' => $this->jadwal_id,
            'guru_id' => $this->guru_id,
            'tanggal' => $this->tanggal,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'materi_ajar' => $this->materi_ajar,
            'kegiatan_pembelajaran' => $this->kegiatan_pembelajaran,
            'metode_pembelajaran' => $this->metode_pembelajaran,
            'jumlah_siswa_hadir' => $this->jumlah_siswa_hadir,
            'jumlah_siswa_tidak_hadir' => $this->jumlah_siswa_tidak_hadir,
            'kendala' => $this->kendala,
            'solusi' => $this->solusi,
            'catatan' => $this->catatan,
            'status' => $this->status
        ];

        if ($this->editMode) {
            $jurnal = JurnalMengajar::findOrFail($this->jurnalId);
            $jurnal->update($data);
            session()->flash('success', 'Jurnal mengajar berhasil diperbarui!');
        } else {
            JurnalMengajar::create($data);
            session()->flash('success', 'Jurnal mengajar berhasil ditambahkan!');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function delete($id)
    {
        $jurnal = JurnalMengajar::findOrFail($id);
        $jurnal->delete();
        session()->flash('success', 'Jurnal mengajar berhasil dihapus!');
    }

    public function submitJurnal($id)
    {
        $jurnal = JurnalMengajar::findOrFail($id);
        if ($jurnal->submit()) {
            session()->flash('success', 'Jurnal mengajar berhasil disubmit!');
        } else {
            session()->flash('error', 'Jurnal tidak dapat disubmit!');
        }
    }

    public function approveJurnal($id)
    {
        $jurnal = JurnalMengajar::findOrFail($id);
        if ($jurnal->approve(Auth::id())) {
            session()->flash('success', 'Jurnal mengajar berhasil disetujui!');
        } else {
            session()->flash('error', 'Jurnal tidak dapat disetujui!');
        }
    }

    public function autoFillPresensi()
    {
        if ($this->jadwal_id && $this->tanggal) {
            $presensi = Presensi::where('jadwal_id', $this->jadwal_id)
                ->whereDate('tanggal', $this->tanggal)
                ->get();

            $this->jumlah_siswa_hadir = $presensi->whereIn('status', ['hadir', 'terlambat'])->count();
            $this->jumlah_siswa_tidak_hadir = $presensi->whereIn('status', ['alpha', 'izin', 'sakit'])->count();

            session()->flash('info', 'Data presensi berhasil dimuat!');
        }
    }

    public function loadJadwalData()
    {
        if ($this->jadwal_id) {
            $jadwal = Jadwal::with(['guru'])->find($this->jadwal_id);
            if ($jadwal) {
                $this->guru_id = $jadwal->guru_id;
                $this->jam_mulai = $jadwal->jam_mulai->format('H:i');
                $this->jam_selesai = $jadwal->jam_selesai->format('H:i');
            }
        }
    }

    public function switchViewMode($mode)
    {
        $this->viewMode = $mode;
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterGuru()
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

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterTahunPelajaran()
    {
        $this->resetPage();
    }

    public function updatingFilterBulan()
    {
        $this->resetPage();
    }

    public function updatingFilterTahun()
    {
        $this->resetPage();
    }

    private function resetForm()
    {
        $this->jurnalId = null;
        $this->jadwal_id = '';
        $this->guru_id = '';
        $this->tanggal = '';
        $this->jam_mulai = '';
        $this->jam_selesai = '';
        $this->materi_ajar = '';
        $this->kegiatan_pembelajaran = '';
        $this->metode_pembelajaran = '';
        $this->jumlah_siswa_hadir = 0;
        $this->jumlah_siswa_tidak_hadir = 0;
        $this->kendala = '';
        $this->solusi = '';
        $this->catatan = '';
        $this->status = 'draft';
        $this->selectedJadwal = null;
        $this->availableJadwal = [];
    }

    private function loadAvailableJadwal()
    {
        $this->availableJadwal = Jadwal::with(['mataPelajaran', 'kelas', 'guru'])
            ->where('is_active', true)
            ->when($this->filterTahunPelajaran, function($q) {
                $q->where('tahun_pelajaran_id', $this->filterTahunPelajaran);
            })
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
    }

    private function getStatistics()
    {
        $tahunPelajaranId = $this->filterTahunPelajaran;
        
        $totalJurnal = JurnalMengajar::when($tahunPelajaranId, function($q) use ($tahunPelajaranId) {
            $q->byTahunPelajaran($tahunPelajaranId);
        })->count();

        $jurnalDraft = JurnalMengajar::byStatus('draft')
            ->when($tahunPelajaranId, function($q) use ($tahunPelajaranId) {
                $q->byTahunPelajaran($tahunPelajaranId);
            })->count();

        $jurnalSubmitted = JurnalMengajar::byStatus('submitted')
            ->when($tahunPelajaranId, function($q) use ($tahunPelajaranId) {
                $q->byTahunPelajaran($tahunPelajaranId);
            })->count();

        $jurnalApproved = JurnalMengajar::byStatus('approved')
            ->when($tahunPelajaranId, function($q) use ($tahunPelajaranId) {
                $q->byTahunPelajaran($tahunPelajaranId);
            })->count();

        return [
            'total_jurnal' => $totalJurnal,
            'jurnal_draft' => $jurnalDraft,
            'jurnal_submitted' => $jurnalSubmitted,
            'jurnal_approved' => $jurnalApproved,
            'completion_rate' => $totalJurnal > 0 ? round(($jurnalApproved / $totalJurnal) * 100, 1) : 0
        ];
    }
}