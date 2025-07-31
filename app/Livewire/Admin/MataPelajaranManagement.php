<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MataPelajaran;

class MataPelajaranManagement extends Component
{
    use WithPagination;

    // Form properties
    public $kode_mapel;
    public $nama_mapel;
    public $deskripsi;
    public $jam_pelajaran = 2;
    public $kategori = 'wajib';
    public $is_active = true;

    // State management
    public $isEditing = false;
    public $editingMataPelajaranId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $filterKategori = '';
    public $filterStatus = '';

    protected $rules = [
        'kode_mapel' => 'required|string|max:10|alpha_num',
        'nama_mapel' => 'required|string|max:100',
        'deskripsi' => 'nullable|string',
        'jam_pelajaran' => 'required|integer|min:1|max:10',
        'kategori' => 'required|in:wajib,pilihan,muatan_lokal',
        'is_active' => 'boolean'
    ];

    protected $messages = [
        'kode_mapel.required' => 'Kode mata pelajaran harus diisi.',
        'kode_mapel.unique' => 'Kode mata pelajaran sudah ada.',
        'kode_mapel.alpha_num' => 'Kode mata pelajaran hanya boleh berisi huruf dan angka.',
        'nama_mapel.required' => 'Nama mata pelajaran harus diisi.',
        'jam_pelajaran.required' => 'Jam pelajaran harus diisi.',
        'jam_pelajaran.min' => 'Jam pelajaran minimal 1.',
        'jam_pelajaran.max' => 'Jam pelajaran maksimal 10.',
        'kategori.required' => 'Kategori harus dipilih.',
        'kategori.in' => 'Kategori tidak valid.'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingFilterKategori()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
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
        $this->kode_mapel = '';
        $this->nama_mapel = '';
        $this->deskripsi = '';
        $this->jam_pelajaran = 2;
        $this->kategori = 'wajib';
        $this->is_active = true;
        $this->isEditing = false;
        $this->editingMataPelajaranId = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->rules['kode_mapel'] .= '|unique:mata_pelajaran,kode_mapel';
        $this->validate();

        try {
            MataPelajaran::create([
                'kode_mapel' => strtoupper($this->kode_mapel),
                'nama_mapel' => $this->nama_mapel,
                'deskripsi' => $this->deskripsi,
                'jam_pelajaran' => $this->jam_pelajaran,
                'kategori' => $this->kategori,
                'is_active' => $this->is_active
            ]);

            $this->resetForm();
            $this->dispatch('mata-pelajaran-created', 'Mata pelajaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            $this->dispatch('mata-pelajaran-error', 'Gagal menambah mata pelajaran: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);
        $this->editingMataPelajaranId = $id;
        $this->kode_mapel = $mataPelajaran->kode_mapel;
        $this->nama_mapel = $mataPelajaran->nama_mapel;
        $this->deskripsi = $mataPelajaran->deskripsi;
        $this->jam_pelajaran = $mataPelajaran->jam_pelajaran;
        $this->kategori = $mataPelajaran->kategori;
        $this->is_active = $mataPelajaran->is_active;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->rules['kode_mapel'] .= '|unique:mata_pelajaran,kode_mapel,' . $this->editingMataPelajaranId;
        $this->validate();

        try {
            $mataPelajaran = MataPelajaran::findOrFail($this->editingMataPelajaranId);
            $mataPelajaran->update([
                'kode_mapel' => strtoupper($this->kode_mapel),
                'nama_mapel' => $this->nama_mapel,
                'deskripsi' => $this->deskripsi,
                'jam_pelajaran' => $this->jam_pelajaran,
                'kategori' => $this->kategori,
                'is_active' => $this->is_active
            ]);

            $this->resetForm();
            $this->dispatch('mata-pelajaran-updated', 'Mata pelajaran berhasil diperbarui!');
        } catch (\Exception $e) {
            $this->dispatch('mata-pelajaran-error', 'Gagal mengupdate mata pelajaran: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $mataPelajaran = MataPelajaran::findOrFail($id);
            $mataPelajaran->delete();
            $this->dispatch('mata-pelajaran-deleted', 'Mata pelajaran berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('mata-pelajaran-error', 'Gagal menghapus mata pelajaran: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $mataPelajaran = MataPelajaran::findOrFail($id);
            $mataPelajaran->update([
                'is_active' => !$mataPelajaran->is_active
            ]);
            
            $status = $mataPelajaran->is_active ? 'diaktifkan' : 'dinonaktifkan';
            $this->dispatch('mata-pelajaran-updated', 'Mata pelajaran berhasil ' . $status . '!');
        } catch (\Exception $e) {
            $this->dispatch('mata-pelajaran-error', 'Gagal mengubah status mata pelajaran: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = MataPelajaran::query()
            ->when($this->search, function ($query) {
                $query->where('kode_mapel', 'like', '%' . $this->search . '%')
                      ->orWhere('nama_mapel', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterKategori, function ($query) {
                $query->where('kategori', $this->filterKategori);
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $mataPelajarans = $query->paginate($this->perPage);

        return view('livewire.admin.mata-pelajaran-management', [
            'mataPelajarans' => $mataPelajarans
        ])->layout('layouts.app');
    }
}
