<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JamPresensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class PengaturanJamPresensi extends Component
{
    use WithPagination;

    // Properties untuk form
    public string $nama_hari = '';
    public string $jam_masuk_mulai = '';
    public string $jam_masuk_selesai = '';
    public string $jam_pulang_mulai = '';
    public string $jam_pulang_selesai = '';
    public bool $is_active = true;
    public string $keterangan = '';

    // Properties untuk modal dan state
    public bool $showModal = false;
    public string $modalTitle = '';
    public int $editingId = 0;
    public string $search = '';

    // Validation rules
    protected array $rules = [
        'nama_hari' => 'required|string|max:20',
        'jam_masuk_mulai' => 'required|date_format:H:i',
        'jam_masuk_selesai' => 'required|date_format:H:i|after:jam_masuk_mulai',
        'jam_pulang_mulai' => 'required|date_format:H:i|after:jam_masuk_selesai',
        'jam_pulang_selesai' => 'required|date_format:H:i|after:jam_pulang_mulai',
        'is_active' => 'boolean',
        'keterangan' => 'nullable|string|max:500',
    ];

    protected array $messages = [
        'nama_hari.required' => 'Nama hari harus diisi.',
        'jam_masuk_mulai.required' => 'Jam masuk mulai harus diisi.',
        'jam_masuk_mulai.date_format' => 'Format jam masuk mulai tidak valid (HH:MM).',
        'jam_masuk_selesai.required' => 'Jam masuk selesai harus diisi.',
        'jam_masuk_selesai.date_format' => 'Format jam masuk selesai tidak valid (HH:MM).',
        'jam_masuk_selesai.after' => 'Jam masuk selesai harus setelah jam masuk mulai.',
        'jam_pulang_mulai.required' => 'Jam pulang mulai harus diisi.',
        'jam_pulang_mulai.date_format' => 'Format jam pulang mulai tidak valid (HH:MM).',
        'jam_pulang_mulai.after' => 'Jam pulang mulai harus setelah jam masuk selesai.',
        'jam_pulang_selesai.required' => 'Jam pulang selesai harus diisi.',
        'jam_pulang_selesai.date_format' => 'Format jam pulang selesai tidak valid (HH:MM).',
        'jam_pulang_selesai.after' => 'Jam pulang selesai harus setelah jam pulang mulai.',
    ];

    public function mount(): void
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
        }
    }

    public function bukaModalTambah(): void
    {
        $this->resetForm();
        $this->showModal = true;
        $this->modalTitle = 'Tambah Pengaturan Jam Presensi';
        $this->editingId = 0;
    }

    public function editJamPresensi(int $id): void
    {
        $jamPresensi = JamPresensi::findOrFail($id);
        
        $this->editingId = $id;
        $this->nama_hari = $jamPresensi->nama_hari;
        
        // Konversi format datetime ke H:i untuk input time
        $this->jam_masuk_mulai = $jamPresensi->jam_masuk_mulai ? 
            Carbon::parse($jamPresensi->jam_masuk_mulai)->format('H:i') : '';
        $this->jam_masuk_selesai = $jamPresensi->jam_masuk_selesai ? 
            Carbon::parse($jamPresensi->jam_masuk_selesai)->format('H:i') : '';
        $this->jam_pulang_mulai = $jamPresensi->jam_pulang_mulai ? 
            Carbon::parse($jamPresensi->jam_pulang_mulai)->format('H:i') : '';
        $this->jam_pulang_selesai = $jamPresensi->jam_pulang_selesai ? 
            Carbon::parse($jamPresensi->jam_pulang_selesai)->format('H:i') : '';
            
        $this->is_active = $jamPresensi->is_active;
        $this->keterangan = $jamPresensi->keterangan ?? '';
        
        $this->showModal = true;
        $this->modalTitle = 'Edit Pengaturan Jam Presensi';
    }

    public function simpanJamPresensi(): void
    {
        $this->validate();

        try {
            // Cek duplikasi nama hari (kecuali saat edit)
            $existingQuery = JamPresensi::where('nama_hari', $this->nama_hari);
            if ($this->editingId > 0) {
                $existingQuery->where('id', '!=', $this->editingId);
            }
            
            if ($existingQuery->exists()) {
                session()->flash('error', 'Pengaturan untuk hari "' . $this->nama_hari . '" sudah ada.');
                return;
            }

            $data = [
                'nama_hari' => $this->nama_hari,
                'jam_masuk_mulai' => $this->jam_masuk_mulai,
                'jam_masuk_selesai' => $this->jam_masuk_selesai,
                'jam_pulang_mulai' => $this->jam_pulang_mulai,
                'jam_pulang_selesai' => $this->jam_pulang_selesai,
                'is_active' => $this->is_active,
                'keterangan' => $this->keterangan,
            ];

            if ($this->editingId > 0) {
                // Update
                $jamPresensi = JamPresensi::findOrFail($this->editingId);
                $jamPresensi->update($data);
                
                \Log::info('Jam presensi berhasil diupdate', [
                    'id' => $this->editingId,
                    'admin_id' => Auth::user()->id,
                    'data' => $data
                ]);
                
                session()->flash('message', 'Pengaturan jam presensi berhasil diperbarui!');
            } else {
                // Create
                $jamPresensi = JamPresensi::create($data);
                
                \Log::info('Jam presensi berhasil ditambahkan', [
                    'id' => $jamPresensi->id,
                    'admin_id' => Auth::user()->id,
                    'data' => $data
                ]);
                
                session()->flash('message', 'Pengaturan jam presensi berhasil ditambahkan!');
            }

            $this->tutupModal();
            
        } catch (\Exception $e) {
            \Log::error('Error saat menyimpan jam presensi: ' . $e->getMessage(), [
                'exception' => $e,
                'admin_id' => Auth::user()->id,
                'data' => $data ?? []
            ]);
            
            session()->flash('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function hapusJamPresensi(int $id): void
    {
        try {
            $jamPresensi = JamPresensi::findOrFail($id);
            $namaHari = $jamPresensi->nama_hari;
            
            $jamPresensi->delete();
            
            \Log::info('Jam presensi berhasil dihapus', [
                'id' => $id,
                'nama_hari' => $namaHari,
                'admin_id' => Auth::user()->id
            ]);
            
            session()->flash('message', 'Pengaturan jam presensi untuk "' . $namaHari . '" berhasil dihapus!');
            
        } catch (\Exception $e) {
            \Log::error('Error saat menghapus jam presensi: ' . $e->getMessage(), [
                'exception' => $e,
                'id' => $id,
                'admin_id' => Auth::user()->id
            ]);
            
            session()->flash('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        }
    }

    public function toggleStatus(int $id): void
    {
        try {
            $jamPresensi = JamPresensi::findOrFail($id);
            $jamPresensi->update(['is_active' => !$jamPresensi->is_active]);
            
            $status = $jamPresensi->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            \Log::info('Status jam presensi berhasil diubah', [
                'id' => $id,
                'nama_hari' => $jamPresensi->nama_hari,
                'status' => $jamPresensi->is_active,
                'admin_id' => Auth::user()->id
            ]);
            
            session()->flash('message', 'Pengaturan jam presensi berhasil ' . $status . '!');
            
        } catch (\Exception $e) {
            \Log::error('Error saat mengubah status jam presensi: ' . $e->getMessage(), [
                'exception' => $e,
                'id' => $id,
                'admin_id' => Auth::user()->id
            ]);
            
            session()->flash('error', 'Terjadi kesalahan saat mengubah status. Silakan coba lagi.');
        }
    }

    public function buatPengaturanDefault(): void
    {
        try {
            // Cek apakah sudah ada pengaturan default
            if (JamPresensi::where('nama_hari', 'default')->exists()) {
                session()->flash('error', 'Pengaturan default sudah ada.');
                return;
            }

            JamPresensi::buatPengaturanDefault();
            
            \Log::info('Pengaturan default jam presensi berhasil dibuat', [
                'admin_id' => Auth::user()->id
            ]);
            
            session()->flash('message', 'Pengaturan default jam presensi berhasil dibuat!');
            
        } catch (\Exception $e) {
            \Log::error('Error saat membuat pengaturan default: ' . $e->getMessage(), [
                'exception' => $e,
                'admin_id' => Auth::user()->id
            ]);
            
            session()->flash('error', 'Terjadi kesalahan saat membuat pengaturan default.');
        }
    }

    public function tutupModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'nama_hari',
            'jam_masuk_mulai',
            'jam_masuk_selesai',
            'jam_pulang_mulai',
            'jam_pulang_selesai',
            'is_active',
            'keterangan',
            'editingId'
        ]);
        $this->is_active = true;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function getDaftarHari(): array
    {
        return JamPresensi::getDaftarHari();
    }

    public function render(): View
    {
        $jamPresensiList = JamPresensi::query()
            ->when($this->search, function ($query) {
                $query->where('nama_hari', 'like', '%' . $this->search . '%')
                      ->orWhere('keterangan', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nama_hari')
            ->paginate(10);

        return view('livewire.admin.pengaturan-jam-presensi', [
            'jamPresensiList' => $jamPresensiList,
            'daftarHari' => $this->getDaftarHari()
        ])->layout('layouts.app');
    }
}