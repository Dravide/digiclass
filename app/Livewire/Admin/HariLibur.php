<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\HariLibur as HariLiburModel;
use App\Services\HariLiburService;
use Illuminate\View\View;
use Carbon\Carbon;

class HariLibur extends Component
{
    use WithPagination;

    public string $tanggal = '';
    public string $tanggal_display = '';
    public string $keterangan = '';
    public bool $is_cuti = false;
    public bool $is_aktif = true;
    
    public bool $showModal = false;
    public string $modalTitle = '';
    public int $editingId = 0;
    
    public string $search = '';
    public string $filterTahun = '';
    public string $filterStatus = '';
    
    protected array $rules = [
        'tanggal' => 'required|date',
        'tanggal_display' => 'required|string|max:255',
        'keterangan' => 'required|string|max:500',
        'is_cuti' => 'boolean',
        'is_aktif' => 'boolean',
    ];
    
    protected array $messages = [
        'tanggal.required' => 'Tanggal harus diisi.',
        'tanggal.date' => 'Format tanggal tidak valid.',
        'tanggal_display.required' => 'Tanggal display harus diisi.',
        'keterangan.required' => 'Keterangan harus diisi.',
        'keterangan.max' => 'Keterangan maksimal 500 karakter.',
    ];

    public function mount(): void
    {
        $this->filterTahun = Carbon::now()->year;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    
    public function updatingFilterTahun(): void
    {
        $this->resetPage();
    }
    
    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function bukaModal(): void
    {
        $this->showModal = true;
        $this->modalTitle = 'Tambah Hari Libur';
        $this->editingId = 0;
        $this->reset(['tanggal', 'tanggal_display', 'keterangan', 'is_cuti', 'is_aktif']);
        $this->is_aktif = true;
    }

    public function editHariLibur(int $id): void
    {
        $hariLibur = HariLiburModel::findOrFail($id);
        
        $this->editingId = $id;
        $this->tanggal = $hariLibur->tanggal;
        $this->tanggal_display = $hariLibur->tanggal_display;
        $this->keterangan = $hariLibur->keterangan;
        $this->is_cuti = $hariLibur->is_cuti;
        $this->is_aktif = $hariLibur->is_aktif;
        
        $this->showModal = true;
        $this->modalTitle = 'Edit Hari Libur';
    }

    public function simpanHariLibur(): void
    {
        $this->validate();
        
        try {
            $data = [
                'tanggal' => $this->tanggal,
                'tanggal_display' => $this->tanggal_display,
                'keterangan' => $this->keterangan,
                'is_cuti' => $this->is_cuti,
                'is_aktif' => $this->is_aktif,
            ];
            
            if ($this->editingId) {
                HariLiburModel::findOrFail($this->editingId)->update($data);
                session()->flash('message', 'Hari libur berhasil diperbarui!');
            } else {
                HariLiburModel::create($data);
                session()->flash('message', 'Hari libur berhasil ditambahkan!');
            }
            
            $this->tutupModal();
            
        } catch (\Exception $e) {
            \Log::error('Error saving hari libur: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function hapusHariLibur(int $id): void
    {
        try {
            HariLiburModel::findOrFail($id)->delete();
            session()->flash('message', 'Hari libur berhasil dihapus!');
        } catch (\Exception $e) {
            \Log::error('Error deleting hari libur: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function toggleStatus(int $id): void
    {
        try {
            $hariLibur = HariLiburModel::findOrFail($id);
            $hariLibur->update(['is_aktif' => !$hariLibur->is_aktif]);
            
            $status = $hariLibur->is_aktif ? 'diaktifkan' : 'dinonaktifkan';
            session()->flash('message', "Hari libur berhasil {$status}!");
        } catch (\Exception $e) {
            \Log::error('Error toggling hari libur status: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengubah status.');
        }
    }

    public function sinkronisasiApi(): void
    {
        try {
            $tahun = $this->filterTahun ?: Carbon::now()->year;
            $service = new HariLiburService();
            $result = $service->sinkronisasiDariApi($tahun);
            
            if ($result['success']) {
                $message = "Sinkronisasi berhasil: {$result['new_count']} data baru, {$result['updated_count']} data diupdate";
                session()->flash('message', $message);
            } else {
                session()->flash('error', 'Gagal melakukan sinkronisasi: ' . $result['message']);
            }
        } catch (\Exception $e) {
            \Log::error('Error syncing hari libur: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat sinkronisasi.');
        }
    }

    public function tutupModal(): void
    {
        $this->showModal = false;
        $this->reset(['tanggal', 'tanggal_display', 'keterangan', 'is_cuti', 'is_aktif', 'editingId']);
    }

    public function render(): View
    {
        $query = HariLiburModel::query();
        
        // Filter berdasarkan pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('keterangan', 'like', '%' . $this->search . '%')
                  ->orWhere('tanggal_display', 'like', '%' . $this->search . '%');
            });
        }
        
        // Filter berdasarkan tahun
        if ($this->filterTahun) {
            $query->whereYear('tanggal', $this->filterTahun);
        }
        
        // Filter berdasarkan status
        if ($this->filterStatus !== '') {
            $query->where('is_aktif', $this->filterStatus === '1');
        }
        
        $hariLibur = $query->orderBy('tanggal', 'asc')->paginate(15);
        
        // Get available years for filter
        $availableYears = HariLiburModel::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
            
        // Add current year if not in list
        $currentYear = Carbon::now()->year;
        if (!in_array($currentYear, $availableYears)) {
            $availableYears[] = $currentYear;
            rsort($availableYears);
        }
        
        return view('livewire.admin.hari-libur', [
            'hariLibur' => $hariLibur,
            'availableYears' => $availableYears
        ])->layout('layouts.app');
    }
}