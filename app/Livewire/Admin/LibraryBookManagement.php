<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LibraryBook;
use Illuminate\Support\Str;

class LibraryBookManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterKategori = '';
    public $filterKondisi = '';
    public $showModal = false;
    public $editMode = false;
    public $bookId;

    // Form fields
    public $kode_buku = '';
    public $judul_buku = '';
    public $pengarang = '';
    public $penerbit = '';
    public $tahun_terbit = '';
    public $isbn = '';
    public $kategori = '';
    public $deskripsi = '';
    public $jumlah_total = 1;
    public $jumlah_tersedia = 1;
    public $lokasi_rak = '';
    public $kondisi = 'baik';
    public $is_active = true;

    protected $rules = [
        'kode_buku' => 'required|string|max:255',
        'judul_buku' => 'required|string|max:255',
        'pengarang' => 'required|string|max:255',
        'penerbit' => 'required|string|max:255',
        'tahun_terbit' => 'required|integer|min:1900|max:' . 2025,
        'isbn' => 'nullable|string|max:255',
        'kategori' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'jumlah_total' => 'required|integer|min:1',
        'jumlah_tersedia' => 'required|integer|min:0',
        'lokasi_rak' => 'nullable|string|max:255',
        'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
        'is_active' => 'boolean'
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterKategori()
    {
        $this->resetPage();
    }

    public function updatedFilterKondisi()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function editBook($id)
    {
        $book = LibraryBook::findOrFail($id);
        $this->bookId = $book->id;
        $this->kode_buku = $book->kode_buku;
        $this->judul_buku = $book->judul_buku;
        $this->pengarang = $book->pengarang;
        $this->penerbit = $book->penerbit;
        $this->tahun_terbit = $book->tahun_terbit;
        $this->isbn = $book->isbn;
        $this->kategori = $book->kategori;
        $this->deskripsi = $book->deskripsi;
        $this->jumlah_total = $book->jumlah_total;
        $this->jumlah_tersedia = $book->jumlah_tersedia;
        $this->lokasi_rak = $book->lokasi_rak;
        $this->kondisi = $book->kondisi;
        $this->is_active = $book->is_active;
        
        $this->showModal = true;
        $this->editMode = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'bookId', 'kode_buku', 'judul_buku', 'pengarang', 'penerbit',
            'tahun_terbit', 'isbn', 'kategori', 'deskripsi', 'jumlah_total',
            'jumlah_tersedia', 'lokasi_rak', 'kondisi', 'is_active'
        ]);
        $this->kondisi = 'baik';
        $this->is_active = true;
        $this->jumlah_total = 1;
        $this->jumlah_tersedia = 1;
    }

    public function save()
    {
        $this->validate();

        if (!$this->editMode) {
            // Generate kode_buku if empty
            if (empty($this->kode_buku)) {
                $this->kode_buku = 'BK' . date('Ymd') . Str::random(4);
            }
        }

        $data = [
            'kode_buku' => $this->kode_buku,
            'judul_buku' => $this->judul_buku,
            'pengarang' => $this->pengarang,
            'penerbit' => $this->penerbit,
            'tahun_terbit' => $this->tahun_terbit,
            'isbn' => $this->isbn,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'jumlah_total' => $this->jumlah_total,
            'jumlah_tersedia' => $this->jumlah_tersedia,
            'lokasi_rak' => $this->lokasi_rak,
            'kondisi' => $this->kondisi,
            'is_active' => $this->is_active
        ];

        if ($this->editMode) {
            LibraryBook::find($this->bookId)->update($data);
            session()->flash('message', 'Buku berhasil diperbarui!');
        } else {
            LibraryBook::create($data);
            session()->flash('message', 'Buku berhasil ditambahkan!');
        }

        $this->closeModal();
    }

    public function deleteBook($id)
    {
        $book = LibraryBook::findOrFail($id);
        
        // Check if book has active borrowings
        if ($book->activeBorrowings()->count() > 0) {
            session()->flash('error', 'Tidak dapat menghapus buku yang sedang dipinjam!');
            return;
        }

        $book->delete();
        session()->flash('message', 'Buku berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $book = LibraryBook::findOrFail($id);
        $book->update(['is_active' => !$book->is_active]);
        
        $status = $book->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('message', "Buku berhasil {$status}!");
    }

    public function render()
    {
        $books = LibraryBook::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_buku', 'like', '%' . $this->search . '%')
                      ->orWhere('judul_buku', 'like', '%' . $this->search . '%')
                      ->orWhere('pengarang', 'like', '%' . $this->search . '%')
                      ->orWhere('penerbit', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterKategori, function ($query) {
                $query->where('kategori', $this->filterKategori);
            })
            ->when($this->filterKondisi, function ($query) {
                $query->where('kondisi', $this->filterKondisi);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = LibraryBook::distinct()->pluck('kategori')->filter();
        
        return view('livewire.admin.library-book-management', [
            'books' => $books,
            'categories' => $categories
        ])->layout('layouts.app');
    }
}
