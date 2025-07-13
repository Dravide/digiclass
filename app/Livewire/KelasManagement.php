<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kelas;
use App\Models\TahunPelajaran;
use App\Models\Guru;

use Illuminate\Validation\Rule;

class KelasManagement extends Component
{
    use WithPagination;



    // Form properties
    public $nama_kelas = '';
    public $tingkat = '';
    public $jurusan = '';
    public $kapasitas = 30;
    public $tahun_pelajaran_id = '';
    public $link_wa = '';
    public $guru_id = '';

    
    // State properties
    public $isEditing = false;
    public $editingKelasId = null;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'nama_kelas';
    public $sortDirection = 'asc';
    
    // Filter properties
    public $filterTingkat = '';
    public $filterJurusan = '';
    public $filterTahunPelajaran = '';

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        // Set default filter to active academic year
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        if ($activeTahunPelajaran) {
            $this->filterTahunPelajaran = $activeTahunPelajaran->id;
            $this->tahun_pelajaran_id = $activeTahunPelajaran->id;
        }
    }

    protected function rules()
    {
        $rules = [
            'nama_kelas' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kelas', 'nama_kelas')->where(function ($query) {
                    return $query->where('tahun_pelajaran_id', $this->tahun_pelajaran_id);
                })
            ],
            'tingkat' => 'required|string|max:10',
            'jurusan' => 'nullable|string|max:100',
            'kapasitas' => 'required|integer|min:1|max:50',
            'tahun_pelajaran_id' => 'required|exists:tahun_pelajarans,id',
            'link_wa' => 'nullable|url|max:255',
            'guru_id' => 'nullable|exists:gurus,id',

        ];

        if ($this->isEditing && $this->editingKelasId) {
            $rules['nama_kelas'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('kelas', 'nama_kelas')->ignore($this->editingKelasId)->where(function ($query) {
                    return $query->where('tahun_pelajaran_id', $this->tahun_pelajaran_id);
                })
            ];
        }

        return $rules;
    }

    protected $messages = [
        'nama_kelas.required' => 'Nama kelas wajib diisi.',
        'nama_kelas.unique' => 'Nama kelas sudah ada untuk tahun pelajaran ini.',
        'tingkat.required' => 'Tingkat wajib diisi.',
        'kapasitas.required' => 'Kapasitas wajib diisi.',
        'kapasitas.min' => 'Kapasitas minimal 1 siswa.',
        'kapasitas.max' => 'Kapasitas maksimal 50 siswa.',
        'tahun_pelajaran_id.required' => 'Tahun pelajaran wajib dipilih.',
        'tahun_pelajaran_id.exists' => 'Tahun pelajaran tidak valid.',
        'link_wa.url' => 'Link WhatsApp harus berupa URL yang valid.',
        'link_wa.max' => 'Link WhatsApp maksimal 255 karakter.',
        'guru_id.exists' => 'Guru yang dipilih tidak valid.'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterTingkat()
    {
        $this->resetPage();
    }

    public function updatingFilterJurusan()
    {
        $this->resetPage();
    }

    public function updatingFilterTahunPelajaran()
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
    }

    public function resetForm()
    {
        $this->nama_kelas = '';
        $this->tingkat = '';
        $this->jurusan = '';
        $this->kapasitas = 30;
        $this->link_wa = '';
        $this->guru_id = '';
        // Keep current filter as default for new kelas
        $this->tahun_pelajaran_id = $this->filterTahunPelajaran ?: TahunPelajaran::where('is_active', true)->first()?->id;

        $this->isEditing = false;
        $this->editingKelasId = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        try {
            Kelas::create([
                'nama_kelas' => $this->nama_kelas,
                'tingkat' => $this->tingkat,
                'jurusan' => $this->jurusan ?: null,
                'kapasitas' => $this->kapasitas,
                'tahun_pelajaran_id' => $this->tahun_pelajaran_id,
                'link_wa' => $this->link_wa ?: null,
                'guru_id' => $this->guru_id ?: null,
            ]);

            $this->resetForm();
            $this->dispatch('kelas-created', 'Kelas berhasil ditambahkan!');
        } catch (\Exception $e) {
            $this->dispatch('kelas-error', 'Gagal menambahkan kelas: ' . $e->getMessage());
        }
    }

    public function edit($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        
        $this->editingKelasId = $kelas->id;
        $this->nama_kelas = $kelas->nama_kelas;
        $this->tingkat = $kelas->tingkat;
        $this->jurusan = $kelas->jurusan;
        $this->kapasitas = $kelas->kapasitas;
        $this->tahun_pelajaran_id = $kelas->tahun_pelajaran_id;
        $this->link_wa = $kelas->link_wa;
        $this->guru_id = $kelas->guru_id;

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        try {
            $kelas = Kelas::findOrFail($this->editingKelasId);
            $kelas->update([
                'nama_kelas' => $this->nama_kelas,
                'tingkat' => $this->tingkat,
                'jurusan' => $this->jurusan ?: null,
                'kapasitas' => $this->kapasitas,
                'tahun_pelajaran_id' => $this->tahun_pelajaran_id,
                'link_wa' => $this->link_wa ?: null,
                'guru_id' => $this->guru_id ?: null,
            ]);

            $this->resetForm();
            $this->dispatch('kelas-updated', 'Kelas berhasil diperbarui!');
        } catch (\Exception $e) {
            $this->dispatch('kelas-error', 'Gagal memperbarui kelas: ' . $e->getMessage());
        }
    }

    public function delete($kelasId)
    {
        try {
            $kelas = Kelas::findOrFail($kelasId);
            
            // Check if kelas has students
            if ($kelas->siswa()->count() > 0) {
                $this->dispatch('kelas-error', 'Tidak dapat menghapus kelas yang masih memiliki siswa!');
                return;
            }

            $kelas->delete();
            $this->dispatch('kelas-deleted', 'Kelas berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('kelas-error', 'Gagal menghapus kelas: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Kelas::with(['tahunPelajaran', 'guru','siswa']);

        // Apply tahun pelajaran filter (default to active if not set)
        if ($this->filterTahunPelajaran) {
            $query->where('tahun_pelajaran_id', $this->filterTahunPelajaran);
        } else {
            // Default to active academic year
            $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
            if ($activeTahunPelajaran) {
                $query->where('tahun_pelajaran_id', $activeTahunPelajaran->id);
            }
        }

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_kelas', 'like', '%' . $this->search . '%')
                  ->orWhere('tingkat', 'like', '%' . $this->search . '%')
                  ->orWhere('jurusan', 'like', '%' . $this->search . '%');
            });
        }

        // Apply filters
        if ($this->filterTingkat) {
            $query->where('tingkat', $this->filterTingkat);
        }

        if ($this->filterJurusan) {
            $query->where('jurusan', $this->filterJurusan);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $kelas = $query->withCount('siswa')->paginate($this->perPage);

     

        // Get unique values for filters based on current tahun pelajaran filter
        $baseQuery = Kelas::query();
        if ($this->filterTahunPelajaran) {
            $baseQuery->where('tahun_pelajaran_id', $this->filterTahunPelajaran);
        } else {
            $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
            if ($activeTahunPelajaran) {
                $baseQuery->where('tahun_pelajaran_id', $activeTahunPelajaran->id);
            }
        }
        
        $tingkatOptions = $baseQuery->distinct()->pluck('tingkat')->filter()->sort();
        $jurusanOptions = $baseQuery->distinct()->pluck('jurusan')->filter()->sort();
        
        // Get all tahun pelajaran for filter dropdown
        $tahunPelajaranOptions = TahunPelajaran::orderBy('nama_tahun_pelajaran', 'desc')->get();
        
        // Get all guru for wali kelas dropdown
        $guruList = Guru::orderBy('nama_guru')->get();
        
        return view('livewire.kelas-management', [
            'kelas' => $kelas,
            'tingkatOptions' => $tingkatOptions,
            'jurusanOptions' => $jurusanOptions,
            'tahunPelajaranOptions' => $tahunPelajaranOptions,
            'guruList' => $guruList
        ])->layout('layouts.app', [
            'title' => 'Manajemen Kelas',
            'page-title' => 'Manajemen Kelas'
        ]);
    }
}
