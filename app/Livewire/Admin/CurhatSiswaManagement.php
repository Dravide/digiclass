<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CurhatSiswa;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CurhatSiswaManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $kategoriFilter = '';
    public $tahunPelajaranFilter = '';
    public $selectedCurhat = null;
    public $showDetailModal = false;
    public $penanganan = '';
    public $status = '';
    
    // Alert properties
    public $showAlert = false;
    public $alertType = 'success';
    public $alertMessage = '';

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->tahunPelajaranFilter = TahunPelajaran::where('is_active', true)->first()->id ?? '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingKategoriFilter()
    {
        $this->resetPage();
    }

    public function updatingTahunPelajaranFilter()
    {
        $this->resetPage();
    }

    public function showDetail($curhatId)
    {
        $this->selectedCurhat = CurhatSiswa::with(['siswa', 'tahunPelajaran'])->find($curhatId);
        $this->penanganan = $this->selectedCurhat->penanganan ?? '';
        $this->status = $this->selectedCurhat->status;
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedCurhat = null;
        $this->penanganan = '';
        $this->status = '';
    }

    public function updateCurhat()
    {
        $this->validate([
            'penanganan' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,diproses,selesai'
        ]);

        try {
            DB::beginTransaction();

            $this->selectedCurhat->update([
                'penanganan' => $this->penanganan,
                'status' => $this->status,
                'tanggal_penanganan' => $this->penanganan ? Carbon::now() : null
            ]);

            DB::commit();

            $this->showAlertMessage('success', 'Curhat berhasil diperbarui!');
            $this->closeDetailModal();
        } catch (\Exception $e) {
            DB::rollback();
            $this->showAlertMessage('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteCurhat($curhatId)
    {
        try {
            $curhat = CurhatSiswa::find($curhatId);
            $curhat->delete();
            
            $this->showAlertMessage('success', 'Curhat berhasil dihapus!');
        } catch (\Exception $e) {
            $this->showAlertMessage('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showAlertMessage($type, $message)
    {
        $this->alertType = $type;
        $this->alertMessage = $message;
        $this->showAlert = true;
    }

    public function hideAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        $query = CurhatSiswa::with(['siswa', 'tahunPelajaran'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('judul', 'like', '%' . $this->search . '%')
                          ->orWhere('isi_curhat', 'like', '%' . $this->search . '%')
                          ->orWhere('nama_pengirim', 'like', '%' . $this->search . '%')
                          ->orWhereHas('siswa', function ($q) {
                              $q->where('nama_siswa', 'like', '%' . $this->search . '%');
                          });
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->kategoriFilter, function ($q) {
                $q->where('kategori', $this->kategoriFilter);
            })
            ->when($this->tahunPelajaranFilter, function ($q) {
                $q->where('tahun_pelajaran_id', $this->tahunPelajaranFilter);
            })
            ->orderBy('created_at', 'desc');

        $curhatList = $query->paginate(10);
        
        $tahunPelajaranList = TahunPelajaran::orderBy('nama_tahun_pelajaran', 'desc')->get();
        
        $kategoriOptions = [
            'akademik' => 'Masalah Akademik',
            'sosial' => 'Masalah Sosial',
            'keluarga' => 'Masalah Keluarga',
            'pribadi' => 'Masalah Pribadi',
            'karir' => 'Konsultasi Karir',
            'lainnya' => 'Lainnya'
        ];

        return view('livewire.admin.curhat-siswa-management', [
            'curhatList' => $curhatList,
            'tahunPelajaranList' => $tahunPelajaranList,
            'kategoriOptions' => $kategoriOptions
        ]);
    }
}