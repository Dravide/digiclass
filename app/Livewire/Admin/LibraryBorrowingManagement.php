<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LibraryBorrowing;
use App\Models\LibraryBook;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LibraryBorrowingManagement extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $filterStatus = '';
    public $filterOverdue = false;
    public $startDate = '';
    public $endDate = '';

    // Modal state
    public $showModal = false;
    public $editMode = false;
    public $borrowingId = null;

    // Form fields
    public $kode_peminjaman = '';
    public $siswa_id = '';
    public $library_book_id = '';
    public $tanggal_pinjam = '';
    public $tanggal_kembali_rencana = '';
    public $tanggal_kembali_aktual = '';
    public $status = 'dipinjam';
    public $denda = 0;
    public $catatan = '';
    public $kondisi_pinjam = 'baik';
    public $kondisi_kembali = 'baik';

    protected $rules = [
        'siswa_id' => 'required|exists:siswa,id',
        'library_book_id' => 'required|exists:library_books,id',
        'tanggal_pinjam' => 'required|date',
        'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
        'tanggal_kembali_aktual' => 'nullable|date',
        'status' => 'required|in:dipinjam,dikembalikan,hilang,rusak',
        'denda' => 'nullable|numeric|min:0',
        'catatan' => 'nullable|string|max:500',
        'kondisi_pinjam' => 'required|in:baik,rusak_ringan,rusak_berat',
        'kondisi_kembali' => 'nullable|in:baik,rusak_ringan,rusak_berat'
    ];

    protected $messages = [
        'siswa_id.required' => 'Siswa harus dipilih',
        'siswa_id.exists' => 'Siswa tidak valid',
        'library_book_id.required' => 'Buku harus dipilih',
        'library_book_id.exists' => 'Buku tidak valid',
        'tanggal_pinjam.required' => 'Tanggal pinjam harus diisi',
        'tanggal_kembali_rencana.required' => 'Tanggal kembali rencana harus diisi',
        'tanggal_kembali_rencana.after' => 'Tanggal kembali harus setelah tanggal pinjam',
        'status.required' => 'Status harus dipilih',
        'kondisi_pinjam.required' => 'Kondisi pinjam harus dipilih'
    ];

    public function mount()
    {
        $this->tanggal_pinjam = now()->format('Y-m-d');
        $this->tanggal_kembali_rencana = now()->addDays(7)->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterOverdue()
    {
        $this->resetPage();
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
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->borrowingId = null;
        $this->kode_peminjaman = '';
        $this->siswa_id = '';
        $this->library_book_id = '';
        $this->tanggal_pinjam = now()->format('Y-m-d');
        $this->tanggal_kembali_rencana = now()->addDays(7)->format('Y-m-d');
        $this->tanggal_kembali_aktual = '';
        $this->status = 'dipinjam';
        $this->denda = 0;
        $this->catatan = '';
        $this->kondisi_pinjam = 'baik';
        $this->kondisi_kembali = 'baik';
    }

    public function editBorrowing($id)
    {
        $borrowing = LibraryBorrowing::findOrFail($id);
        
        $this->borrowingId = $borrowing->id;
        $this->kode_peminjaman = $borrowing->kode_peminjaman;
        $this->siswa_id = $borrowing->siswa_id;
        $this->library_book_id = $borrowing->library_book_id;
        $this->tanggal_pinjam = $borrowing->tanggal_pinjam->format('Y-m-d');
        $this->tanggal_kembali_rencana = $borrowing->tanggal_kembali_rencana->format('Y-m-d');
        $this->tanggal_kembali_aktual = $borrowing->tanggal_kembali_aktual ? $borrowing->tanggal_kembali_aktual->format('Y-m-d') : '';
        $this->status = $borrowing->status;
        $this->denda = $borrowing->denda;
        $this->catatan = $borrowing->catatan;
        $this->kondisi_pinjam = $borrowing->kondisi_pinjam;
        $this->kondisi_kembali = $borrowing->kondisi_kembali;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'siswa_id' => $this->siswa_id,
                'library_book_id' => $this->library_book_id,
                'tanggal_pinjam' => $this->tanggal_pinjam,
                'tanggal_kembali_rencana' => $this->tanggal_kembali_rencana,
                'tanggal_kembali_aktual' => $this->tanggal_kembali_aktual ?: null,
                'status' => $this->status,
                'denda' => $this->denda ?: 0,
                'catatan' => $this->catatan,
                'kondisi_pinjam' => $this->kondisi_pinjam,
                'kondisi_kembali' => $this->kondisi_kembali,
                'petugas_id' => Auth::id()
            ];

            if ($this->editMode) {
                $borrowing = LibraryBorrowing::findOrFail($this->borrowingId);
                $oldStatus = $borrowing->status;
                
                $borrowing->update($data);
                
                // Update book availability if status changed
                if ($oldStatus !== $this->status) {
                    $this->updateBookAvailability($borrowing->library_book_id, $oldStatus, $this->status);
                }
                
                session()->flash('message', 'Data peminjaman berhasil diperbarui!');
            } else {
                // Generate kode_peminjaman if not provided
                if (empty($this->kode_peminjaman)) {
                    $data['kode_peminjaman'] = 'PJM-' . date('Ymd') . '-' . str_pad(LibraryBorrowing::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $data['kode_peminjaman'] = $this->kode_peminjaman;
                }
                
                $borrowing = LibraryBorrowing::create($data);
                
                // Update book availability
                $this->updateBookAvailability($borrowing->library_book_id, null, $this->status);
                
                session()->flash('message', 'Data peminjaman berhasil ditambahkan!');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function updateBookAvailability($bookId, $oldStatus, $newStatus)
    {
        $book = LibraryBook::find($bookId);
        if (!$book) return;

        // If creating new borrowing or changing from returned to borrowed
        if (($oldStatus === null || $oldStatus === 'dikembalikan') && in_array($newStatus, ['dipinjam', 'hilang', 'rusak'])) {
            $book->decrement('jumlah_tersedia');
        }
        // If returning book or changing from borrowed to returned
        elseif (in_array($oldStatus, ['dipinjam', 'hilang', 'rusak']) && $newStatus === 'dikembalikan') {
            $book->increment('jumlah_tersedia');
        }
    }

    public function returnBook($id)
    {
        try {
            $borrowing = LibraryBorrowing::findOrFail($id);
            
            if ($borrowing->status !== 'dipinjam') {
                session()->flash('error', 'Buku ini tidak dalam status dipinjam!');
                return;
            }

            $borrowing->update([
                'status' => 'dikembalikan',
                'tanggal_kembali_aktual' => now(),
                'petugas_id' => Auth::id()
            ]);

            // Update book availability
            $this->updateBookAvailability($borrowing->library_book_id, 'dipinjam', 'dikembalikan');

            session()->flash('message', 'Buku berhasil dikembalikan!');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteBorrowing($id)
    {
        try {
            $borrowing = LibraryBorrowing::findOrFail($id);
            
            // Update book availability if needed
            if (in_array($borrowing->status, ['dipinjam', 'hilang', 'rusak'])) {
                $this->updateBookAvailability($borrowing->library_book_id, $borrowing->status, 'dikembalikan');
            }
            
            $borrowing->delete();
            session()->flash('message', 'Data peminjaman berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = LibraryBorrowing::with(['siswa', 'libraryBook', 'petugas'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('kode_peminjaman', 'like', '%' . $this->search . '%')
                        ->orWhereHas('siswa', function ($q) {
                            $q->where('nama_siswa', 'like', '%' . $this->search . '%')
                              ->orWhere('nis', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('libraryBook', function ($q) {
                            $q->where('judul_buku', 'like', '%' . $this->search . '%')
                              ->orWhere('kode_buku', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->when($this->filterOverdue, function ($q) {
                $q->where('status', 'dipinjam')
                  ->where('tanggal_kembali_rencana', '<', now());
            })
            ->when($this->startDate, function ($q) {
                $q->whereDate('tanggal_pinjam', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($q) {
                $q->whereDate('tanggal_pinjam', '<=', $this->endDate);
            })
            ->orderBy('created_at', 'desc');

        $borrowings = $query->paginate(10);
        
        $students = Siswa::select('id', 'nama_siswa', 'nis')->orderBy('nama_siswa')->get();
        $books = LibraryBook::active()->available()->select('id', 'kode_buku', 'judul_buku', 'jumlah_tersedia')->orderBy('judul_buku')->get();

        return view('livewire.admin.library-borrowing-management', compact('borrowings', 'students', 'books'))->layout('layouts.app');
    }
}
