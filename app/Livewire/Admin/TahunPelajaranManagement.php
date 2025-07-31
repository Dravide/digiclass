<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunPelajaran;
use Carbon\Carbon;

class TahunPelajaranManagement extends Component
{
    use WithPagination;

    // Form properties
    public $nama_tahun_pelajaran;
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $keterangan;

    // State management
    public $isEditing = false;
    public $editingTahunPelajaranId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $rules = [
        'nama_tahun_pelajaran' => 'required|string|max:255',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        'keterangan' => 'nullable|string'
    ];

    protected $messages = [
        'nama_tahun_pelajaran.required' => 'Nama tahun pelajaran harus diisi.',
        'nama_tahun_pelajaran.unique' => 'Nama tahun pelajaran sudah ada.',
        'tanggal_mulai.required' => 'Tanggal mulai harus diisi.',
        'tanggal_selesai.required' => 'Tanggal selesai harus diisi.',
        'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
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
        $this->nama_tahun_pelajaran = '';
        $this->tanggal_mulai = '';
        $this->tanggal_selesai = '';
        $this->keterangan = '';
        $this->isEditing = false;
        $this->editingTahunPelajaranId = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->rules['nama_tahun_pelajaran'] .= '|unique:tahun_pelajarans,nama_tahun_pelajaran';
        $this->validate();

        try {
            TahunPelajaran::create([
                'nama_tahun_pelajaran' => $this->nama_tahun_pelajaran,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'keterangan' => $this->keterangan,
                'is_active' => false
            ]);

            $this->resetForm();
            $this->dispatch('tahun-pelajaran-created');
        } catch (\Exception $e) {
            $this->dispatch('tahun-pelajaran-error', message: 'Gagal menambah tahun pelajaran: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $tahunPelajaran = TahunPelajaran::findOrFail($id);
        $this->editingTahunPelajaranId = $id;
        $this->nama_tahun_pelajaran = $tahunPelajaran->nama_tahun_pelajaran;
        $this->tanggal_mulai = $tahunPelajaran->tanggal_mulai->format('Y-m-d');
        $this->tanggal_selesai = $tahunPelajaran->tanggal_selesai->format('Y-m-d');
        $this->keterangan = $tahunPelajaran->keterangan;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->rules['nama_tahun_pelajaran'] .= '|unique:tahun_pelajarans,nama_tahun_pelajaran,' . $this->editingTahunPelajaranId;
        $this->validate();

        try {
            $tahunPelajaran = TahunPelajaran::findOrFail($this->editingTahunPelajaranId);
            $tahunPelajaran->update([
                'nama_tahun_pelajaran' => $this->nama_tahun_pelajaran,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'keterangan' => $this->keterangan
            ]);

            $this->resetForm();
            $this->dispatch('tahun-pelajaran-updated');
        } catch (\Exception $e) {
            $this->dispatch('tahun-pelajaran-error', message: 'Gagal mengupdate tahun pelajaran: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $tahunPelajaran = TahunPelajaran::findOrFail($id);
            
            // Cek apakah tahun pelajaran memiliki kelas
            if ($tahunPelajaran->kelas()->count() > 0) {
                $this->dispatch('tahun-pelajaran-error', message: 'Tidak dapat menghapus tahun pelajaran yang masih memiliki kelas.');
                return;
            }

            $tahunPelajaran->delete();
            $this->dispatch('tahun-pelajaran-deleted');
        } catch (\Exception $e) {
            $this->dispatch('tahun-pelajaran-error', message: 'Gagal menghapus tahun pelajaran: ' . $e->getMessage());
        }
    }

    public function activate($id)
    {
        try {
            $tahunPelajaran = TahunPelajaran::findOrFail($id);
            $tahunPelajaran->activate();
            $this->dispatch('tahun-pelajaran-activated');
        } catch (\Exception $e) {
            $this->dispatch('tahun-pelajaran-error', message: 'Gagal mengaktifkan tahun pelajaran: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = TahunPelajaran::query()
            ->when($this->search, function ($query) {
                $query->where('nama_tahun_pelajaran', 'like', '%' . $this->search . '%')
                      ->orWhere('keterangan', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $tahunPelajarans = $query->paginate($this->perPage);

        return view('livewire.admin.tahun-pelajaran-management', [
            'tahunPelajarans' => $tahunPelajarans
        ])->layout('layouts.app');
    }
}
