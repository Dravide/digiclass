<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Nilai;
use App\Models\Tugas;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MataPelajaran;

class NilaiManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterKelas = '';
    public $filterMataPelajaran = '';
    public $filterTugas = '';
    public $filterStatus = '';

    // Form properties
    public $showModal = false;
    public $editMode = false;
    public $nilaiId;
    public $tugas_id;
    public $siswa_id;
    public $nilaiForm;
    public $status_pengumpulan = 'belum_mengumpulkan';
    public $tanggal_pengumpulan;
    public $catatan_guru;
    public $catatan_siswa;
    public $file_tugas;

    // Bulk input properties
    public $showBulkModal = false;
    public $selectedTugas;
    public $nilaiSiswa = [];

    protected $rules = [
        'tugas_id' => 'required|exists:tugas,id',
        'siswa_id' => 'required|exists:siswa,id',
        'nilaiForm' => 'nullable|numeric|min:0|max:100',
        'status_pengumpulan' => 'required|in:belum_mengumpulkan,terlambat,tepat_waktu',
        'tanggal_pengumpulan' => 'nullable|date',
        'catatan_guru' => 'nullable|string',
        'catatan_siswa' => 'nullable|string',
        'file_tugas' => 'nullable|string'
    ];

    public function render()
    {
        $query = Nilai::with(['tugas.mataPelajaran', 'tugas.kelas', 'siswa'])
            ->when($this->search, function($q) {
                $q->whereHas('siswa', function($q) {
                    $q->where('nama_siswa', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('tugas', function($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterKelas, function($q) {
                $q->whereHas('tugas', function($q) {
                    $q->where('kelas_id', $this->filterKelas);
                });
            })
            ->when($this->filterMataPelajaran, function($q) {
                $q->whereHas('tugas', function($q) {
                    $q->where('mata_pelajaran_id', $this->filterMataPelajaran);
                });
            })
            ->when($this->filterTugas, function($q) {
                $q->where('tugas_id', $this->filterTugas);
            })
            ->when($this->filterStatus, function($q) {
                $q->where('status_pengumpulan', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc');

        try {
            $nilai = $query->paginate(15);
        } catch (\Exception $e) {
            // If pagination fails, return empty collection
            $nilai = collect();
            \Log::error('Nilai pagination error: ' . $e->getMessage());
        }
        
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get();
        $tugas = Tugas::with(['mataPelajaran', 'kelas'])->orderBy('created_at', 'desc')->get();
        $siswa = Siswa::orderBy('nama_siswa')->get();

        return view('livewire.nilai-management', [
            'nilai' => $nilai ?? collect(),
            'kelas' => $kelas ?? collect(),
            'mataPelajaran' => $mataPelajaran ?? collect(),
            'tugas' => $tugas ?? collect(),
            'siswa' => $siswa ?? collect()
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $nilai = Nilai::findOrFail($id);
        $this->nilaiId = $nilai->id;
        $this->tugas_id = $nilai->tugas_id;
        $this->siswa_id = $nilai->siswa_id;
        $this->nilaiForm = $nilai->nilai;
        $this->status_pengumpulan = $nilai->status_pengumpulan;
        $this->tanggal_pengumpulan = $nilai->tanggal_pengumpulan?->format('Y-m-d\TH:i');
        $this->catatan_guru = $nilai->catatan_guru;
        $this->catatan_siswa = $nilai->catatan_siswa;
        $this->file_tugas = $nilai->file_tugas;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'tugas_id' => $this->tugas_id,
            'siswa_id' => $this->siswa_id,
            'nilai' => $this->nilaiForm,
            'status_pengumpulan' => $this->status_pengumpulan,
            'tanggal_pengumpulan' => $this->tanggal_pengumpulan,
            'catatan_guru' => $this->catatan_guru,
            'catatan_siswa' => $this->catatan_siswa,
            'file_tugas' => $this->file_tugas
        ];

        if ($this->editMode) {
            $nilai = Nilai::findOrFail($this->nilaiId);
            $nilai->update($data);
            session()->flash('success', 'Nilai berhasil diperbarui!');
        } else {
            Nilai::create($data);
            session()->flash('success', 'Nilai berhasil ditambahkan!');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function delete($id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();
        session()->flash('success', 'Nilai berhasil dihapus!');
    }

    public function openBulkInput($tugasId)
    {
        $this->selectedTugas = Tugas::with(['kelas.kelasSiswa.siswa', 'nilai'])->findOrFail($tugasId);
        
        // Initialize nilai siswa array
        $this->nilaiSiswa = [];
        foreach ($this->selectedTugas->kelas->kelasSiswa as $kelasSiswa) {
            $existingNilai = $this->selectedTugas->nilai->where('siswa_id', $kelasSiswa->siswa_id)->first();
            $this->nilaiSiswa[$kelasSiswa->siswa_id] = [
                'id' => $existingNilai?->id,
                'nilai' => $existingNilai?->nilai ?? '',
                'status_pengumpulan' => $existingNilai?->status_pengumpulan ?? 'belum_mengumpulkan',
                'catatan_guru' => $existingNilai?->catatan_guru ?? ''
            ];
        }
        
        $this->showBulkModal = true;
    }

    public function saveBulkNilai()
    {
        foreach ($this->nilaiSiswa as $siswaId => $data) {
            if ($data['id']) {
                // Update existing
                $nilai = Nilai::find($data['id']);
                if ($nilai) {
                    $nilai->update([
                        'nilai' => $data['nilai'] ?: null,
                        'status_pengumpulan' => $data['status_pengumpulan'],
                        'catatan_guru' => $data['catatan_guru']
                    ]);
                }
            } else {
                // Create new
                Nilai::create([
                    'tugas_id' => $this->selectedTugas->id,
                    'siswa_id' => $siswaId,
                    'nilai' => $data['nilai'] ?: null,
                    'status_pengumpulan' => $data['status_pengumpulan'],
                    'catatan_guru' => $data['catatan_guru']
                ]);
            }
        }

        session()->flash('success', 'Nilai berhasil disimpan!');
        $this->showBulkModal = false;
        $this->selectedTugas = null;
        $this->nilaiSiswa = [];
    }

    public function resetForm()
    {
        $this->nilaiId = null;
        $this->tugas_id = '';
        $this->siswa_id = '';
        $this->nilaiForm = '';
        $this->status_pengumpulan = 'belum_mengumpulkan';
        $this->tanggal_pengumpulan = '';
        $this->catatan_guru = '';
        $this->catatan_siswa = '';
        $this->file_tugas = '';
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeBulkModal()
    {
        $this->showBulkModal = false;
        $this->selectedTugas = null;
        $this->nilaiSiswa = [];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterKelas()
    {
        $this->resetPage();
    }

    public function updatingFilterMataPelajaran()
    {
        $this->resetPage();
    }

    public function updatingFilterTugas()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
}