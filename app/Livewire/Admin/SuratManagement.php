<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Surat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SuratManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editMode = false;
    public $suratId;
    
    // Form fields
    public $jenis_surat = '';
    public $perihal = '';
    public $isi_surat = '';
    public $penerima = '';
    public $jabatan_penerima = '';
    public $tanggal_surat = '';
    
    // Search and filter
    public $search = '';
    public $statusFilter = '';
    public $jenisSuratFilter = '';
    
    protected $rules = [
        'jenis_surat' => 'required|string|max:255',
        'perihal' => 'required|string|max:255',
        'isi_surat' => 'required|string',
        'penerima' => 'required|string|max:255',
        'jabatan_penerima' => 'nullable|string|max:255',
        'tanggal_surat' => 'required|date'
    ];

    protected $messages = [
        'jenis_surat.required' => 'Jenis surat harus diisi.',
        'perihal.required' => 'Perihal harus diisi.',
        'isi_surat.required' => 'Isi surat harus diisi.',
        'penerima.required' => 'Penerima harus diisi.',
        'tanggal_surat.required' => 'Tanggal surat harus diisi.',
        'tanggal_surat.date' => 'Format tanggal tidak valid.'
    ];

    public function mount()
    {
        $this->tanggal_surat = date('Y-m-d');
    }

    public function render()
    {
        $query = Surat::with('creator')
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('nomor_surat', 'like', '%' . $this->search . '%')
                        ->orWhere('perihal', 'like', '%' . $this->search . '%')
                        ->orWhere('penerima', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->jenisSuratFilter, function ($q) {
                $q->where('jenis_surat', $this->jenisSuratFilter);
            })
            ->orderBy('created_at', 'desc');

        $surat = $query->paginate(10);
        
        $jenisSuratOptions = Surat::distinct()->pluck('jenis_surat')->filter()->toArray();
        
        return view('livewire.admin.surat-management', [
            'surat' => $surat,
            'jenisSuratOptions' => $jenisSuratOptions
        ])->layout('layouts.app');
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->suratId = null;
        $this->jenis_surat = '';
        $this->perihal = '';
        $this->isi_surat = '';
        $this->penerima = '';
        $this->jabatan_penerima = '';
        $this->tanggal_surat = date('Y-m-d');
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        try {
            $nomorSurat = Surat::generateNomorSurat($this->jenis_surat);
            
            Surat::create([
                'nomor_surat' => $nomorSurat,
                'jenis_surat' => $this->jenis_surat,
                'perihal' => $this->perihal,
                'isi_surat' => $this->isi_surat,
                'penerima' => $this->penerima,
                'jabatan_penerima' => $this->jabatan_penerima,
                'tanggal_surat' => $this->tanggal_surat,
                'created_by' => Auth::id(),
                'status' => 'draft'
            ]);

            session()->flash('success', 'Surat berhasil dibuat dengan nomor: ' . $nomorSurat);
            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat surat: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $surat = Surat::findOrFail($id);
        
        $this->suratId = $surat->id;
        $this->jenis_surat = $surat->jenis_surat;
        $this->perihal = $surat->perihal;
        $this->isi_surat = $surat->isi_surat;
        $this->penerima = $surat->penerima;
        $this->jabatan_penerima = $surat->jabatan_penerima;
        $this->tanggal_surat = $surat->tanggal_surat->format('Y-m-d');
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        try {
            $surat = Surat::findOrFail($this->suratId);
            
            // Only allow editing if not signed
            if ($surat->isSigned()) {
                session()->flash('error', 'Surat yang sudah ditandatangani tidak dapat diedit.');
                return;
            }
            
            $surat->update([
                'jenis_surat' => $this->jenis_surat,
                'perihal' => $this->perihal,
                'isi_surat' => $this->isi_surat,
                'penerima' => $this->penerima,
                'jabatan_penerima' => $this->jabatan_penerima,
                'tanggal_surat' => $this->tanggal_surat
            ]);

            session()->flash('success', 'Surat berhasil diperbarui.');
            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui surat: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $surat = Surat::findOrFail($id);
            
            // Only allow deletion if not signed
            if ($surat->isSigned()) {
                session()->flash('error', 'Surat yang sudah ditandatangani tidak dapat dihapus.');
                return;
            }
            
            // Delete QR code file if exists
            if ($surat->qr_code_path && Storage::exists($surat->qr_code_path)) {
                Storage::delete($surat->qr_code_path);
            }
            
            $surat->delete();
            session()->flash('success', 'Surat berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus surat: ' . $e->getMessage());
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->jenisSuratFilter = '';
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingJenisSuratFilter()
    {
        $this->resetPage();
    }
}