<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class JadwalManagement extends Component
{
    use WithPagination;

    // Form properties
    public $jadwalId;
    public $tahun_pelajaran_id;
    public $guru_id;
    public $mata_pelajaran_id;
    public $kelas_id;
    public $hari;
    public $jam_mulai;
    public $jam_selesai;
    public $jam_ke;
    public $keterangan;
    public $is_active = true;

    // Filter properties
    public $filterGuru;
    public $filterKelas;
    public $filterHari;
    public $filterMataPelajaran;

    // Modal state
    public $showModal = false;
    public $isEdit = false;

    // Search
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        $this->tahun_pelajaran_id = $activeTahunPelajaran?->id;
    }

    protected function rules()
    {
        return [
            'tahun_pelajaran_id' => 'required|exists:tahun_pelajarans,id',
            'guru_id' => 'required|exists:gurus,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jam_ke' => 'required|integer|min:1|max:10',
            'keterangan' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ];
    }

    protected $messages = [
        'tahun_pelajaran_id.required' => 'Tahun pelajaran harus dipilih.',
        'guru_id.required' => 'Guru harus dipilih.',
        'mata_pelajaran_id.required' => 'Mata pelajaran harus dipilih.',
        'kelas_id.required' => 'Kelas harus dipilih.',
        'hari.required' => 'Hari harus dipilih.',
        'jam_mulai.required' => 'Jam mulai harus diisi.',
        'jam_selesai.required' => 'Jam selesai harus diisi.',
        'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        'jam_ke.required' => 'Jam ke harus diisi.',
        'jam_ke.min' => 'Jam ke minimal 1.',
        'jam_ke.max' => 'Jam ke maksimal 10.'
    ];

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        $this->jadwalId = $jadwal->id;
        $this->tahun_pelajaran_id = $jadwal->tahun_pelajaran_id;
        $this->guru_id = $jadwal->guru_id;
        $this->mata_pelajaran_id = $jadwal->mata_pelajaran_id;
        $this->kelas_id = $jadwal->kelas_id;
        $this->hari = $jadwal->hari;
        $this->jam_mulai = $jadwal->jam_mulai->format('H:i');
        $this->jam_selesai = $jadwal->jam_selesai->format('H:i');
        $this->jam_ke = $jadwal->jam_ke;
        $this->keterangan = $jadwal->keterangan;
        $this->is_active = $jadwal->is_active;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Cek bentrok jadwal
        $data = [
            'tahun_pelajaran_id' => $this->tahun_pelajaran_id,
            'guru_id' => $this->guru_id,
            'kelas_id' => $this->kelas_id,
            'hari' => $this->hari,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'jam_ke' => $this->jam_ke
        ];

        $excludeId = $this->isEdit ? $this->jadwalId : null;
        
        if (Jadwal::checkBentrok($data, $excludeId)) {
            session()->flash('error', 'Jadwal bentrok! Guru atau kelas sudah memiliki jadwal pada waktu tersebut.');
            return;
        }

        try {
            DB::beginTransaction();

            $jadwalData = [
                'tahun_pelajaran_id' => $this->tahun_pelajaran_id,
                'guru_id' => $this->guru_id,
                'mata_pelajaran_id' => $this->mata_pelajaran_id,
                'kelas_id' => $this->kelas_id,
                'hari' => $this->hari,
                'jam_mulai' => $this->jam_mulai,
                'jam_selesai' => $this->jam_selesai,
                'jam_ke' => $this->jam_ke,
                'keterangan' => $this->keterangan,
                'is_active' => $this->is_active
            ];

            if ($this->isEdit) {
                Jadwal::findOrFail($this->jadwalId)->update($jadwalData);
                session()->flash('success', 'Jadwal berhasil diperbarui!');
            } else {
                Jadwal::create($jadwalData);
                session()->flash('success', 'Jadwal berhasil ditambahkan!');
            }

            DB::commit();
            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            Jadwal::findOrFail($id)->delete();
            session()->flash('success', 'Jadwal berhasil dihapus!');
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->jadwalId = null;
        $this->guru_id = null;
        $this->mata_pelajaran_id = null;
        $this->kelas_id = null;
        $this->hari = null;
        $this->jam_mulai = null;
        $this->jam_selesai = null;
        $this->jam_ke = null;
        $this->keterangan = null;
        $this->is_active = true;
        $this->resetErrorBag();
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

    public function updatingFilterHari()
    {
        $this->resetPage();
    }

    public function updatingFilterMataPelajaran()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Jadwal::with(['guru', 'mataPelajaran', 'kelas', 'tahunPelajaran'])
            ->currentYear();

        // Apply filters
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('guru', function ($subQ) {
                    $subQ->where('nama_guru', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('mataPelajaran', function ($subQ) {
                    $subQ->where('nama_mapel', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('kelas', function ($subQ) {
                    $subQ->where('nama_kelas', 'like', '%' . $this->search . '%');
                });
            });
        }

        if ($this->filterGuru) {
            $query->where('guru_id', $this->filterGuru);
        }

        if ($this->filterKelas) {
            $query->where('kelas_id', $this->filterKelas);
        }

        if ($this->filterHari) {
            $query->where('hari', $this->filterHari);
        }

        if ($this->filterMataPelajaran) {
            $query->where('mata_pelajaran_id', $this->filterMataPelajaran);
        }

        $jadwals = $query->orderBy('hari')
            ->orderBy('jam_ke')
            ->paginate(15);

        // Get data for dropdowns
        $gurus = Guru::orderBy('nama_guru')->get();
        $mataPelajarans = MataPelajaran::active()->orderBy('nama_mapel')->get();
        $kelas = Kelas::whereHas('tahunPelajaran', function ($q) {
            $q->where('is_active', true);
        })->orderBy('nama_kelas')->get();
        $tahunPelajarans = TahunPelajaran::orderBy('tanggal_mulai', 'desc')->get();

        $hariOptions = [
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu'
        ];

        return view('livewire.admin.jadwal-management', compact(
            'jadwals',
            'gurus',
            'mataPelajarans',
            'kelas',
            'tahunPelajarans',
            'hariOptions'
        ))->layout('layouts.app',[
            'title' => 'Manajemen Jadwal',
            'page-title' => 'Manajemen Jadwal'
        ]);
    }
}
