<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class LibraryBorrowing extends Model
{
    protected $fillable = [
        'kode_peminjaman',
        'siswa_id',
        'library_book_id',
        'petugas_id',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'denda',
        'catatan',
        'kondisi_pinjam',
        'kondisi_kembali'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
        'denda' => 'integer'
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function libraryBook(): BelongsTo
    {
        return $this->belongsTo(LibraryBook::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'dipinjam');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'dipinjam')
                    ->where('tanggal_kembali_rencana', '<', now());
    }

    public function isOverdue(): bool
    {
        return $this->status === 'dipinjam' && 
               $this->tanggal_kembali_rencana < now();
    }

    public function getDaysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        
        return now()->diffInDays($this->tanggal_kembali_rencana);
    }
}
