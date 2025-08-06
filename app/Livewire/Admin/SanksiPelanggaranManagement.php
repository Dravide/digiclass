<?php

namespace App\Livewire\Admin;

use App\Models\SanksiPelanggaran;
use Livewire\Component;
use Livewire\WithPagination;

class SanksiPelanggaranManagement extends Component
{
    use WithPagination;

    // Form properties
    public $tingkat_pelanggaran;
    public $poin_minimum;
    public $poin_maksimum;
    public $jenis_sanksi;
    public $deskripsi_sanksi;
    public $penanggungjawab;
    public $is_active = true;

    // Modal and state properties
    public $showModal = false;
    public $editMode = false;
    public $editingId = null;
    public $showDeleteModal = false;
    public $deletingId = null;

    // Search and filter properties
    public $search = '';
    public $filterTingkatPelanggaran = '';
    public $filterPenanggungjawab = '';
    public $filterStatus = '';
    public $perPage = 10;

    protected $rules = [
        'tingkat_pelanggaran' => 'required|string|in:ringan,sedang,berat,sangat_berat',
        'poin_minimum' => 'required|integer|min:1',
        'poin_maksimum' => 'required|integer|min:1',
        'jenis_sanksi' => 'required|string|max:200',
        'deskripsi_sanksi' => 'nullable|string|max:1000',
        'penanggungjawab' => 'required|string|max:100',
        'is_active' => 'boolean'
    ];

    protected $messages = [
        'tingkat_pelanggaran.required' => 'Tingkat pelanggaran wajib dipilih.',
        'poin_minimum.required' => 'Poin minimum wajib diisi.',
        'poin_maksimum.required' => 'Poin maksimum wajib diisi.',
        'jenis_sanksi.required' => 'Jenis sanksi wajib diisi.',
        'penanggungjawab.required' => 'Penanggungjawab wajib dipilih.',
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterTingkatPelanggaran()
    {
        $this->resetPage();
    }

    public function updatedFilterPenanggungjawab()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedPoinMinimum()
    {
        // Auto-adjust poin_maksimum if it's less than poin_minimum
        if ($this->poin_maksimum && $this->poin_minimum && $this->poin_maksimum < $this->poin_minimum) {
            $this->poin_maksimum = $this->poin_minimum;
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $sanksi = SanksiPelanggaran::findOrFail($id);
        $this->editingId = $id;
        $this->tingkat_pelanggaran = $sanksi->tingkat_pelanggaran;
        $this->poin_minimum = $sanksi->poin_minimum;
        $this->poin_maksimum = $sanksi->poin_maksimum;
        $this->jenis_sanksi = $sanksi->jenis_sanksi;
        $this->deskripsi_sanksi = $sanksi->deskripsi_sanksi;
        $this->penanggungjawab = $sanksi->penanggungjawab;
        $this->is_active = $sanksi->is_active;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        // Add custom validation for poin range
        $this->rules['poin_maksimum'] = 'required|integer|min:' . ($this->poin_minimum ?: 1);
        
        $this->validate();

        // Check for overlapping ranges in the same tingkat_pelanggaran
        $query = SanksiPelanggaran::where('tingkat_pelanggaran', $this->tingkat_pelanggaran)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereBetween('poin_minimum', [$this->poin_minimum, $this->poin_maksimum])
                  ->orWhereBetween('poin_maksimum', [$this->poin_minimum, $this->poin_maksimum])
                  ->orWhere(function ($subQ) {
                      $subQ->where('poin_minimum', '<=', $this->poin_minimum)
                           ->where('poin_maksimum', '>=', $this->poin_maksimum);
                  });
            });

        if ($this->editMode) {
            $query->where('id', '!=', $this->editingId);
        }

        if ($query->exists()) {
            $this->addError('poin_minimum', 'Rentang poin bertumpang tindih dengan sanksi lain pada tingkat pelanggaran yang sama.');
            return;
        }

        try {
            if ($this->editMode) {
                $sanksi = SanksiPelanggaran::findOrFail($this->editingId);
                $sanksi->update([
                    'tingkat_pelanggaran' => $this->tingkat_pelanggaran,
                    'poin_minimum' => $this->poin_minimum,
                    'poin_maksimum' => $this->poin_maksimum,
                    'jenis_sanksi' => $this->jenis_sanksi,
                    'deskripsi_sanksi' => $this->deskripsi_sanksi,
                    'penanggungjawab' => $this->penanggungjawab,
                    'is_active' => $this->is_active
                ]);
                session()->flash('success', 'Sanksi pelanggaran berhasil diperbarui.');
            } else {
                SanksiPelanggaran::create([
                    'tingkat_pelanggaran' => $this->tingkat_pelanggaran,
                    'poin_minimum' => $this->poin_minimum,
                    'poin_maksimum' => $this->poin_maksimum,
                    'jenis_sanksi' => $this->jenis_sanksi,
                    'deskripsi_sanksi' => $this->deskripsi_sanksi,
                    'penanggungjawab' => $this->penanggungjawab,
                    'is_active' => $this->is_active
                ]);
                session()->flash('success', 'Sanksi pelanggaran berhasil ditambahkan.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $sanksi = SanksiPelanggaran::findOrFail($id);
            $sanksi->update(['is_active' => !$sanksi->is_active]);
            
            $status = $sanksi->is_active ? 'diaktifkan' : 'dinonaktifkan';
            session()->flash('success', 'Sanksi pelanggaran berhasil ' . $status . '.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $sanksi = SanksiPelanggaran::findOrFail($this->deletingId);
            $sanksi->delete();
            session()->flash('success', 'Sanksi pelanggaran berhasil dihapus.');
            $this->showDeleteModal = false;
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->tingkat_pelanggaran = '';
        $this->poin_minimum = '';
        $this->poin_maksimum = '';
        $this->jenis_sanksi = '';
        $this->deskripsi_sanksi = '';
        $this->penanggungjawab = '';
        $this->is_active = true;
        $this->editingId = null;
        $this->deletingId = null;
        $this->resetErrorBag();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterTingkatPelanggaran = '';
        $this->filterPenanggungjawab = '';
        $this->filterStatus = '';
        $this->resetPage();
    }

    public function render()
    {
        $sanksiQuery = SanksiPelanggaran::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('jenis_sanksi', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi_sanksi', 'like', '%' . $this->search . '%')
                      ->orWhere('penanggungjawab', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterTingkatPelanggaran, function ($query) {
                $query->where('tingkat_pelanggaran', $this->filterTingkatPelanggaran);
            })
            ->when($this->filterPenanggungjawab, function ($query) {
                $query->where('penanggungjawab', $this->filterPenanggungjawab);
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus);
            })
            ->orderBy('tingkat_pelanggaran')
            ->orderBy('poin_minimum');

        $sanksiPerPage = $sanksiQuery->paginate($this->perPage);

        $tingkatPelanggaranOptions = SanksiPelanggaran::getAvailableTingkatPelanggaran();
        $penanggungjawabOptions = SanksiPelanggaran::getAvailablePenanggungjawab();

        return view('livewire.admin.sanksi-pelanggaran-management', [
            'sanksiPerPage' => $sanksiPerPage,
            'tingkatPelanggaranOptions' => $tingkatPelanggaranOptions,
            'penanggungjawabOptions' => $penanggungjawabOptions
        ])->layout('layouts.app');
    }
}