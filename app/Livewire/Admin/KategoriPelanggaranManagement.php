<?php

namespace App\Livewire\Admin;

use App\Models\KategoriPelanggaran;
use Livewire\Component;
use Livewire\WithPagination;

class KategoriPelanggaranManagement extends Component
{
    use WithPagination;

    // Form properties
    public $kode_kategori;
    public $nama_kategori;
    public $deskripsi;

    // Modal and state properties
    public $showModal = false;
    public $editMode = false;
    public $editingId = null;
    public $showDeleteModal = false;
    public $deletingId = null;

    // Search and filter properties
    public $search = '';
    public $perPage = 10;

    protected $rules = [
        'kode_kategori' => 'required|string|max:10|unique:kategori_pelanggaran,kode_kategori',
        'nama_kategori' => 'required|string|max:100',
        'deskripsi' => 'nullable|string|max:500'
    ];

    protected $messages = [
        'kode_kategori.required' => 'Kode kategori wajib diisi.',
        'kode_kategori.unique' => 'Kode kategori sudah digunakan.',
        'nama_kategori.required' => 'Nama kategori wajib diisi.',
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $kategori = KategoriPelanggaran::findOrFail($id);
        $this->editingId = $id;
        $this->kode_kategori = $kategori->kode_kategori;
        $this->nama_kategori = $kategori->nama_kategori;
        $this->deskripsi = $kategori->deskripsi;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editMode) {
            $this->rules['kode_kategori'] = 'required|string|max:10|unique:kategori_pelanggaran,kode_kategori,' . $this->editingId;
        }

        $this->validate();

        try {
            if ($this->editMode) {
                $kategori = KategoriPelanggaran::findOrFail($this->editingId);
                $kategori->update([
                    'kode_kategori' => $this->kode_kategori,
                    'nama_kategori' => $this->nama_kategori,
                    'deskripsi' => $this->deskripsi
                ]);
                session()->flash('success', 'Kategori pelanggaran berhasil diperbarui.');
            } else {
                KategoriPelanggaran::create([
                    'kode_kategori' => $this->kode_kategori,
                    'nama_kategori' => $this->nama_kategori,
                    'deskripsi' => $this->deskripsi
                ]);
                session()->flash('success', 'Kategori pelanggaran berhasil ditambahkan.');
            }

            $this->closeModal();
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
            $kategori = KategoriPelanggaran::findOrFail($this->deletingId);
            
            // Check if kategori has related jenis pelanggaran
            if ($kategori->jenisPelanggaran()->count() > 0) {
                session()->flash('error', 'Kategori tidak dapat dihapus karena masih memiliki jenis pelanggaran terkait.');
                $this->showDeleteModal = false;
                return;
            }

            $kategori->delete();
            session()->flash('success', 'Kategori pelanggaran berhasil dihapus.');
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
        $this->kode_kategori = '';
        $this->nama_kategori = '';
        $this->deskripsi = '';
        $this->editingId = null;
        $this->deletingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $kategoris = KategoriPelanggaran::query()
            ->when($this->search, function ($query) {
                $query->where('kode_kategori', 'like', '%' . $this->search . '%')
                      ->orWhere('nama_kategori', 'like', '%' . $this->search . '%');
            })
            ->withCount('jenisPelanggaran')
            ->orderBy('kode_kategori')
            ->paginate($this->perPage);

        return view('livewire.admin.kategori-pelanggaran-management', [
            'kategoris' => $kategoris
        ])->layout('layouts.app');
    }
}