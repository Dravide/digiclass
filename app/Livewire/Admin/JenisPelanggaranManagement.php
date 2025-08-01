<?php

namespace App\Livewire\Admin;

use App\Models\JenisPelanggaran;
use App\Models\KategoriPelanggaran;
use Livewire\Component;
use Livewire\WithPagination;

class JenisPelanggaranManagement extends Component
{
    use WithPagination;

    // Form properties
    public $kategori_pelanggaran_id;
    public $kode_pelanggaran;
    public $nama_pelanggaran;
    public $deskripsi_pelanggaran;
    public $poin_pelanggaran;
    public $tingkat_pelanggaran;
    public $is_active = true;

    // Modal and state properties
    public $showModal = false;
    public $editMode = false;
    public $editingId = null;
    public $showDeleteModal = false;
    public $deletingId = null;

    // Search and filter properties
    public $search = '';
    public $filterKategori = '';
    public $filterTingkat = '';
    public $filterStatus = '';
    public $perPage = 10;

    protected $rules = [
        'kategori_pelanggaran_id' => 'required|exists:kategori_pelanggaran,id',
        'kode_pelanggaran' => 'required|string|max:10',
        'nama_pelanggaran' => 'required|string|max:200',
        'deskripsi_pelanggaran' => 'nullable|string|max:1000',
        'poin_pelanggaran' => 'required|integer|min:1|max:500',
        'tingkat_pelanggaran' => 'required|in:ringan,sedang,berat',
        'is_active' => 'boolean'
    ];

    protected $messages = [
        'kategori_pelanggaran_id.required' => 'Kategori pelanggaran wajib dipilih.',
        'kode_pelanggaran.required' => 'Kode pelanggaran wajib diisi.',
        'nama_pelanggaran.required' => 'Nama pelanggaran wajib diisi.',
        'poin_pelanggaran.required' => 'Poin pelanggaran wajib diisi.',
        'poin_pelanggaran.min' => 'Poin pelanggaran minimal 1.',
        'poin_pelanggaran.max' => 'Poin pelanggaran maksimal 500.',
        'tingkat_pelanggaran.required' => 'Tingkat pelanggaran wajib dipilih.',
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterKategori()
    {
        $this->resetPage();
    }

    public function updatedFilterTingkat()
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

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $jenis = JenisPelanggaran::findOrFail($id);
        $this->editingId = $id;
        $this->kategori_pelanggaran_id = $jenis->kategori_pelanggaran_id;
        $this->kode_pelanggaran = $jenis->kode_pelanggaran;
        $this->nama_pelanggaran = $jenis->nama_pelanggaran;
        $this->deskripsi_pelanggaran = $jenis->deskripsi_pelanggaran;
        $this->poin_pelanggaran = $jenis->poin_pelanggaran;
        $this->tingkat_pelanggaran = $jenis->tingkat_pelanggaran;
        $this->is_active = $jenis->is_active;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        // Add unique validation for kode_pelanggaran within the same kategori
        if ($this->editMode) {
            $this->rules['kode_pelanggaran'] = 'required|string|max:10|unique:jenis_pelanggaran,kode_pelanggaran,' . $this->editingId . ',id,kategori_pelanggaran_id,' . $this->kategori_pelanggaran_id;
        } else {
            $this->rules['kode_pelanggaran'] = 'required|string|max:10|unique:jenis_pelanggaran,kode_pelanggaran,NULL,id,kategori_pelanggaran_id,' . $this->kategori_pelanggaran_id;
        }

        $this->validate();

        try {
            if ($this->editMode) {
                $jenis = JenisPelanggaran::findOrFail($this->editingId);
                $jenis->update([
                    'kategori_pelanggaran_id' => $this->kategori_pelanggaran_id,
                    'kode_pelanggaran' => $this->kode_pelanggaran,
                    'nama_pelanggaran' => $this->nama_pelanggaran,
                    'deskripsi_pelanggaran' => $this->deskripsi_pelanggaran,
                    'poin_pelanggaran' => $this->poin_pelanggaran,
                    'tingkat_pelanggaran' => $this->tingkat_pelanggaran,
                    'is_active' => $this->is_active
                ]);
                session()->flash('success', 'Jenis pelanggaran berhasil diperbarui.');
            } else {
                JenisPelanggaran::create([
                    'kategori_pelanggaran_id' => $this->kategori_pelanggaran_id,
                    'kode_pelanggaran' => $this->kode_pelanggaran,
                    'nama_pelanggaran' => $this->nama_pelanggaran,
                    'deskripsi_pelanggaran' => $this->deskripsi_pelanggaran,
                    'poin_pelanggaran' => $this->poin_pelanggaran,
                    'tingkat_pelanggaran' => $this->tingkat_pelanggaran,
                    'is_active' => $this->is_active
                ]);
                session()->flash('success', 'Jenis pelanggaran berhasil ditambahkan.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $jenis = JenisPelanggaran::findOrFail($id);
            $jenis->update(['is_active' => !$jenis->is_active]);
            
            $status = $jenis->is_active ? 'diaktifkan' : 'dinonaktifkan';
            session()->flash('success', 'Jenis pelanggaran berhasil ' . $status . '.');
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
            $jenis = JenisPelanggaran::findOrFail($this->deletingId);
            $jenis->delete();
            session()->flash('success', 'Jenis pelanggaran berhasil dihapus.');
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
        $this->kategori_pelanggaran_id = '';
        $this->kode_pelanggaran = '';
        $this->nama_pelanggaran = '';
        $this->deskripsi_pelanggaran = '';
        $this->poin_pelanggaran = '';
        $this->tingkat_pelanggaran = '';
        $this->is_active = true;
        $this->editingId = null;
        $this->deletingId = null;
        $this->resetErrorBag();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterKategori = '';
        $this->filterTingkat = '';
        $this->filterStatus = '';
        $this->resetPage();
    }

    public function render()
    {
        $jenisQuery = JenisPelanggaran::with('kategoriPelanggaran')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_pelanggaran', 'like', '%' . $this->search . '%')
                      ->orWhere('nama_pelanggaran', 'like', '%' . $this->search . '%')
                      ->orWhereHas('kategoriPelanggaran', function ($kategoriQuery) {
                          $kategoriQuery->where('nama_kategori', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->filterKategori, function ($query) {
                $query->where('kategori_pelanggaran_id', $this->filterKategori);
            })
            ->when($this->filterTingkat, function ($query) {
                $query->where('tingkat_pelanggaran', $this->filterTingkat);
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus);
            })
            ->orderBy('kategori_pelanggaran_id')
            ->orderBy('kode_pelanggaran');

        $jenisPerPage = $jenisQuery->paginate($this->perPage);

        $kategoris = KategoriPelanggaran::orderBy('nama_kategori')->get();
        $tingkatOptions = JenisPelanggaran::getAvailableTingkats();

        return view('livewire.admin.jenis-pelanggaran-management', [
            'jenisPerPage' => $jenisPerPage,
            'kategoris' => $kategoris,
            'tingkatOptions' => $tingkatOptions
        ])->layout('layouts.app');
    }
}