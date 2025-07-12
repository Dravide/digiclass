<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perpustakaan extends Model
{
    protected $table = 'perpustakaan';
    
    protected $fillable = [
        'siswa_id',
        'terpenuhi',
        'keterangan',
        'tanggal_pemenuhan'
    ];

    protected $casts = [
        'terpenuhi' => 'boolean',
        'tanggal_pemenuhan' => 'datetime'
    ];

    // Relationship dengan siswa
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    // Scope untuk perpustakaan yang sudah terpenuhi
    public function scopeTerpenuhi($query)
    {
        return $query->where('terpenuhi', true);
    }

    // Scope untuk perpustakaan yang belum terpenuhi
    public function scopeBelumTerpenuhi($query)
    {
        return $query->where('terpenuhi', false);
    }

    // Method untuk menandai perpustakaan sebagai terpenuhi
    public function markAsTerpenuhi($keterangan = null)
    {
        $this->update([
            'terpenuhi' => true,
            'tanggal_pemenuhan' => now(),
            'keterangan' => $keterangan ?? $this->keterangan
        ]);
    }

    // Method untuk menandai perpustakaan sebagai belum terpenuhi
    public function markAsBelumTerpenuhi($keterangan = null)
    {
        $this->update([
            'terpenuhi' => false,
            'tanggal_pemenuhan' => null,
            'keterangan' => $keterangan ?? $this->keterangan
        ]);
    }

    // Accessor untuk mendapatkan status dalam format string
    public function getStatusAttribute(): string
    {
        return $this->terpenuhi ? 'Terpenuhi' : 'Belum Terpenuhi';
    }

    // Accessor untuk mendapatkan badge class berdasarkan status
    public function getBadgeClassAttribute(): string
    {
        return $this->terpenuhi ? 'badge-success' : 'badge-warning';
    }
}
