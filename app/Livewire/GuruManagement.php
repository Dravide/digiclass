<?php

namespace App\Livewire;

use App\Models\Guru;
use App\Models\MataPelajaran;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GuruManagement extends Component
{
    use WithPagination;

    // Form properties
    public $nama_guru;
    public $nip;
    public $email;
    public $telepon;
    public $is_wali_kelas = false;
    public $mata_pelajaran_id;

    // State management
    public $isEditing = false;
    public $editingGuruId;

    // Search and filter
    public $search = '';
    public $perPage = 10;
    public $sortField = 'nama_guru';
    public $sortDirection = 'asc';
    public $filterWaliKelas = '';

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'nama_guru' => 'required|string|max:100',
            'nip' => [
                'required',
                'string',
                'max:20',
                Rule::unique('gurus', 'nip')->ignore($this->editingGuruId)
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('gurus', 'email')->ignore($this->editingGuruId)
            ],
            'telepon' => 'required|string|max:15',
            'is_wali_kelas' => 'boolean',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
        ];
    }

    protected $messages = [
        'nama_guru.required' => 'Nama guru harus diisi.',
        'nama_guru.max' => 'Nama guru maksimal 100 karakter.',
        'nip.required' => 'NIP harus diisi.',
        'nip.unique' => 'NIP sudah terdaftar.',
        'nip.max' => 'NIP maksimal 20 karakter.',
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'email.max' => 'Email maksimal 100 karakter.',
        'telepon.required' => 'Telepon harus diisi.',
        'telepon.max' => 'Telepon maksimal 15 karakter.',
        'mata_pelajaran_id.exists' => 'Mata pelajaran yang dipilih tidak valid.',
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
        $this->reset([
            'nama_guru',
            'nip',
            'email',
            'telepon',
            'is_wali_kelas',
            'mata_pelajaran_id',
            'isEditing',
            'editingGuruId'
        ]);
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        try {
            // Get mata pelajaran name if ID is provided
            $mataPelajaranName = null;
            if ($this->mata_pelajaran_id) {
                $mataPelajaran = MataPelajaran::find($this->mata_pelajaran_id);
                $mataPelajaranName = $mataPelajaran ? $mataPelajaran->nama_mapel : null;
            }

            Guru::create([
                'nama_guru' => $this->nama_guru,
                'nip' => $this->nip,
                'email' => $this->email,
                'telepon' => $this->telepon,
                'is_wali_kelas' => $this->is_wali_kelas,
                'mata_pelajaran' => $mataPelajaranName,
            ]);

            $this->resetForm();
            $this->dispatch('guru-created', 'Guru berhasil ditambahkan!');
        } catch (\Exception $e) {
            $this->dispatch('guru-error', 'Gagal menambahkan guru: ' . $e->getMessage());
        }
    }

    public function edit($guruId)
    {
        try {
            $guru = Guru::findOrFail($guruId);
            
            $this->editingGuruId = $guru->id;
            $this->nama_guru = $guru->nama_guru;
            $this->nip = $guru->nip;
            $this->email = $guru->email;
            $this->telepon = $guru->telepon;
            $this->is_wali_kelas = $guru->is_wali_kelas;
            
            // Find mata pelajaran ID based on name
            $this->mata_pelajaran_id = null;
            if ($guru->mata_pelajaran) {
                $mataPelajaran = MataPelajaran::where('nama_mapel', $guru->mata_pelajaran)->first();
                $this->mata_pelajaran_id = $mataPelajaran ? $mataPelajaran->id : null;
            }
            
            $this->isEditing = true;
            
            $this->resetValidation();
        } catch (\Exception $e) {
            $this->dispatch('guru-error', 'Gagal memuat data guru: ' . $e->getMessage());
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $guru = Guru::findOrFail($this->editingGuruId);
            
            // Get mata pelajaran name if ID is provided
            $mataPelajaranName = null;
            if ($this->mata_pelajaran_id) {
                $mataPelajaran = MataPelajaran::find($this->mata_pelajaran_id);
                $mataPelajaranName = $mataPelajaran ? $mataPelajaran->nama_mapel : null;
            }
            
            $guru->update([
                'nama_guru' => $this->nama_guru,
                'nip' => $this->nip,
                'email' => $this->email,
                'telepon' => $this->telepon,
                'is_wali_kelas' => $this->is_wali_kelas,
                'mata_pelajaran' => $mataPelajaranName,
            ]);

            $this->resetForm();
            $this->dispatch('guru-updated', 'Guru berhasil diperbarui!');
        } catch (\Exception $e) {
            $this->dispatch('guru-error', 'Gagal memperbarui guru: ' . $e->getMessage());
        }
    }

    public function delete($guruId)
    {
        try {
            $guru = Guru::findOrFail($guruId);
            
            // Check if guru has associated siswa (if they are wali kelas)
            if ($guru->is_wali_kelas && $guru->siswa()->count() > 0) {
                $this->dispatch('guru-error', 'Tidak dapat menghapus guru yang masih menjadi wali kelas dan memiliki siswa yang dibimbing!');
                return;
            }
            
            $guru->delete();
            
            $this->dispatch('guru-deleted', 'Guru berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('guru-error', 'Gagal menghapus guru: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Guru::withCount('siswa')
            ->when($this->search, function ($query) {
                $query->where('nama_guru', 'like', '%' . $this->search . '%')
                      ->orWhere('nip', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('mata_pelajaran', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterWaliKelas !== '', function ($query) {
                $query->where('is_wali_kelas', $this->filterWaliKelas);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $gurus = $query->paginate($this->perPage);
        
        // Get active mata pelajaran for dropdown
        $mataPelajaranList = MataPelajaran::active()->orderBy('nama_mapel')->get();

        return view('livewire.guru-management', [
            'gurus' => $gurus,
            'mataPelajaranList' => $mataPelajaranList,
        ])->layout('layouts.app');
    }
}
