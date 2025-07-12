<?php

namespace App\Livewire;

use App\Models\Perpustakaan;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class PerpustakaanManagement extends Component
{
    use WithPagination;

    // Form properties
    public $siswa_id;
    public $terpenuhi;
    public $keterangan;
    public $tanggal_pemenuhan;

    // State management
    public $isEditing = false;
    public $editingPerpustakaanId;

    // Search and filter
    public $search = '';
    public $filterTerpenuhi = '';
    public $filterTahunPelajaran = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'siswa_id' => [
                'required',
                'exists:siswa,id',
                Rule::unique('perpustakaan', 'siswa_id')->ignore($this->editingPerpustakaanId)
            ],
            'terpenuhi' => 'required|boolean',
            'keterangan' => 'nullable|string|max:500',
            'tanggal_pemenuhan' => 'nullable|date',
        ];
    }

    protected $messages = [
        'siswa_id.required' => 'Siswa harus dipilih.',
        'siswa_id.exists' => 'Siswa yang dipilih tidak valid.',
        'siswa_id.unique' => 'Data perpustakaan untuk siswa ini sudah ada.',
        'terpenuhi.required' => 'Status harus dipilih.',
        'terpenuhi.boolean' => 'Status tidak valid.',
        'keterangan.max' => 'Keterangan maksimal 500 karakter.',
        'tanggal_pemenuhan.date' => 'Format tanggal tidak valid.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterTerpenuhi()
    {
        $this->resetPage();
    }

    public function updatingFilterTahunPelajaran()
    {
        $this->resetPage();
    }

    public function mount()
    {
        // Set default filter to active academic year
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        if ($activeTahunPelajaran) {
            $this->filterTahunPelajaran = $activeTahunPelajaran->id;
        }
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset([
            'siswa_id',
            'terpenuhi',
            'keterangan',
            'tanggal_pemenuhan',
            'isEditing',
            'editingPerpustakaanId'
        ]);
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        try {
            Perpustakaan::create([
                'siswa_id' => $this->siswa_id,
                'terpenuhi' => $this->terpenuhi,
                'keterangan' => $this->keterangan,
                'tanggal_pemenuhan' => $this->tanggal_pemenuhan,
            ]);

            $this->resetForm();
            $this->dispatch('perpustakaan-created', 'Data perpustakaan berhasil ditambahkan!');
        } catch (\Exception $e) {
            $this->dispatch('perpustakaan-error', 'Gagal menambahkan data perpustakaan: ' . $e->getMessage());
        }
    }

    public function edit($perpustakaanId)
    {
        try {
            $perpustakaan = Perpustakaan::findOrFail($perpustakaanId);
            
            $this->editingPerpustakaanId = $perpustakaan->id;
            $this->siswa_id = $perpustakaan->siswa_id;
            $this->terpenuhi = $perpustakaan->terpenuhi;
            $this->keterangan = $perpustakaan->keterangan;
            $this->tanggal_pemenuhan = $perpustakaan->tanggal_pemenuhan ? $perpustakaan->tanggal_pemenuhan->format('Y-m-d') : null;
            $this->isEditing = true;
            
            $this->resetValidation();
        } catch (\Exception $e) {
            $this->dispatch('perpustakaan-error', 'Gagal memuat data perpustakaan: ' . $e->getMessage());
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $perpustakaan = Perpustakaan::findOrFail($this->editingPerpustakaanId);
            
            $perpustakaan->update([
                'siswa_id' => $this->siswa_id,
                'terpenuhi' => $this->terpenuhi,
                'keterangan' => $this->keterangan,
                'tanggal_pemenuhan' => $this->tanggal_pemenuhan,
            ]);

            $this->resetForm();
            $this->dispatch('perpustakaan-updated', 'Data perpustakaan berhasil diperbarui!');
        } catch (\Exception $e) {
            $this->dispatch('perpustakaan-error', 'Gagal memperbarui data perpustakaan: ' . $e->getMessage());
        }
    }

    public function delete($perpustakaanId)
    {
        try {
            $perpustakaan = Perpustakaan::findOrFail($perpustakaanId);
            $perpustakaan->delete();
            
            $this->dispatch('perpustakaan-deleted', 'Data perpustakaan berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('perpustakaan-error', 'Gagal menghapus data perpustakaan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Determine tahun pelajaran filter
        $tahunPelajaranFilter = $this->filterTahunPelajaran;
        if (!$tahunPelajaranFilter) {
            $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
            $tahunPelajaranFilter = $activeTahunPelajaran ? $activeTahunPelajaran->id : null;
        }

        $query = Perpustakaan::with(['siswa', 'siswa.kelas', 'siswa.tahunPelajaran'])
            ->when($this->search, function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('nama_siswa', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterTerpenuhi !== '', function ($query) {
                $query->where('terpenuhi', $this->filterTerpenuhi);
            })
            ->when($tahunPelajaranFilter, function ($query) use ($tahunPelajaranFilter) {
                $query->whereHas('siswa', function ($q) use ($tahunPelajaranFilter) {
                    $q->where('tahun_pelajaran_id', $tahunPelajaranFilter);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $perpustakaan = $query->paginate($this->perPage);
        
        // Get siswa options for dropdown (exclude those already in perpustakaan unless editing)
        $siswaOptions = Siswa::with(['kelas', 'tahunPelajaran'])
            ->when($tahunPelajaranFilter, function ($query) use ($tahunPelajaranFilter) {
                $query->where('tahun_pelajaran_id', $tahunPelajaranFilter);
            })
            ->when(!$this->isEditing, function ($query) {
                $query->whereNotIn('id', Perpustakaan::pluck('siswa_id'));
            })
            ->when($this->isEditing, function ($query) {
                $query->where(function ($q) {
                    $q->whereNotIn('id', Perpustakaan::where('id', '!=', $this->editingPerpustakaanId)->pluck('siswa_id'))
                      ->orWhere('id', $this->siswa_id);
                });
            })
            ->orderBy('nama_siswa')
            ->get();

        // Get tahun pelajaran options
        $tahunPelajaranOptions = TahunPelajaran::orderBy('nama_tahun_pelajaran', 'desc')->get();

        return view('livewire.perpustakaan-management', [
            'perpustakaan' => $perpustakaan,
            'siswaOptions' => $siswaOptions,
            'tahunPelajaranOptions' => $tahunPelajaranOptions,
        ])->layout('layouts.app');
    }
}
