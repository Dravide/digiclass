<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LibraryAttendance;
use App\Models\Siswa;
use Carbon\Carbon;

class LibraryAttendanceManagement extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $filterStatus = '';
    public $filterDate = '';
    public $startDate = '';
    public $endDate = '';

    // Modal state
    public $showModal = false;
    public $editMode = false;
    public $attendanceId = null;

    // Form fields
    public $siswa_id = '';
    public $tanggal = '';
    public $jam_masuk = '';
    public $jam_keluar = '';
    public $keperluan = '';
    public $status = 'hadir';
    public $catatan = '';

    protected $rules = [
        'siswa_id' => 'required|exists:siswa,id',
        'tanggal' => 'required|date',
        'jam_masuk' => 'required',
        'jam_keluar' => 'nullable',
        'keperluan' => 'required|string|max:255',
        'status' => 'required|in:hadir,keluar,izin',
        'catatan' => 'nullable|string|max:500'
    ];

    protected $messages = [
        'siswa_id.required' => 'Siswa harus dipilih',
        'siswa_id.exists' => 'Siswa tidak valid',
        'tanggal.required' => 'Tanggal harus diisi',
        'jam_masuk.required' => 'Jam masuk harus diisi',
        'keperluan.required' => 'Keperluan harus diisi',
        'status.required' => 'Status harus dipilih'
    ];

    public function mount()
    {
        $this->tanggal = now()->format('Y-m-d');
        $this->jam_masuk = now()->format('H:i');
        $this->filterDate = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterDate()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->attendanceId = null;
        $this->siswa_id = '';
        $this->tanggal = now()->format('Y-m-d');
        $this->jam_masuk = now()->format('H:i');
        $this->jam_keluar = '';
        $this->keperluan = '';
        $this->status = 'hadir';
        $this->catatan = '';
    }

    public function editAttendance($id)
    {
        $attendance = LibraryAttendance::findOrFail($id);
        
        $this->attendanceId = $attendance->id;
        $this->siswa_id = $attendance->siswa_id;
        $this->tanggal = $attendance->tanggal->format('Y-m-d');
        $this->jam_masuk = $attendance->jam_masuk ? $attendance->jam_masuk->format('H:i') : '';
        $this->jam_keluar = $attendance->jam_keluar ? $attendance->jam_keluar->format('H:i') : '';
        $this->keperluan = $attendance->keperluan;
        $this->status = $attendance->status;
        $this->catatan = $attendance->catatan;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'siswa_id' => $this->siswa_id,
                'tanggal' => $this->tanggal,
                'jam_masuk' => $this->jam_masuk,
                'jam_keluar' => $this->jam_keluar ?: null,
                'keperluan' => $this->keperluan,
                'status' => $this->status,
                'catatan' => $this->catatan
            ];

            if ($this->editMode) {
                $attendance = LibraryAttendance::findOrFail($this->attendanceId);
                $attendance->update($data);
                session()->flash('message', 'Data kehadiran berhasil diperbarui!');
            } else {
                // Check if attendance already exists for this student and date
                $existingAttendance = LibraryAttendance::where('siswa_id', $this->siswa_id)
                    ->where('tanggal', $this->tanggal)
                    ->first();
                
                if ($existingAttendance) {
                    session()->flash('error', 'Data kehadiran untuk siswa ini pada tanggal tersebut sudah ada!');
                    return;
                }
                
                LibraryAttendance::create($data);
                session()->flash('message', 'Data kehadiran berhasil ditambahkan!');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function checkOut($id)
    {
        try {
            $attendance = LibraryAttendance::findOrFail($id);
            
            if ($attendance->status !== 'hadir') {
                session()->flash('error', 'Siswa ini tidak dalam status hadir!');
                return;
            }

            if ($attendance->jam_keluar) {
                session()->flash('error', 'Siswa ini sudah melakukan check out!');
                return;
            }

            $attendance->update([
                'jam_keluar' => now(),
                'status' => 'keluar'
            ]);

            session()->flash('message', 'Check out berhasil!');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteAttendance($id)
    {
        try {
            $attendance = LibraryAttendance::findOrFail($id);
            $attendance->delete();
            session()->flash('message', 'Data kehadiran berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function quickCheckIn($siswaId)
    {
        try {
            // Check if already checked in today
            $existingAttendance = LibraryAttendance::where('siswa_id', $siswaId)
                ->where('tanggal', now()->format('Y-m-d'))
                ->first();
            
            if ($existingAttendance) {
                session()->flash('error', 'Siswa ini sudah melakukan check in hari ini!');
                return;
            }

            LibraryAttendance::create([
                'siswa_id' => $siswaId,
                'tanggal' => now()->format('Y-m-d'),
                'jam_masuk' => now(),
                'keperluan' => 'Membaca/Belajar',
                'status' => 'hadir'
            ]);

            session()->flash('message', 'Quick check in berhasil!');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = LibraryAttendance::with('siswa')
            ->when($this->search, function ($q) {
                $q->whereHas('siswa', function ($query) {
                    $query->where('nama_siswa', 'like', '%' . $this->search . '%')
                          ->orWhere('nis', 'like', '%' . $this->search . '%');
                })
                ->orWhere('keperluan', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->when($this->filterDate, function ($q) {
                $q->whereDate('tanggal', $this->filterDate);
            })
            ->when($this->startDate, function ($q) {
                $q->whereDate('tanggal', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($q) {
                $q->whereDate('tanggal', '<=', $this->endDate);
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc');

        $attendances = $query->paginate(15);
        
        $students = Siswa::select('id', 'nama_siswa', 'nis')->orderBy('nama_siswa')->get();
        
        // Statistics for today
        $todayStats = [
            'total_hadir' => LibraryAttendance::whereDate('tanggal', now())->where('status', 'hadir')->count(),
            'total_keluar' => LibraryAttendance::whereDate('tanggal', now())->where('status', 'keluar')->count(),
            'total_izin' => LibraryAttendance::whereDate('tanggal', now())->where('status', 'izin')->count(),
        ];

        return view('livewire.admin.library-attendance-management', compact('attendances', 'students', 'todayStats'))
        ->layout('layouts.app');
    }
}
