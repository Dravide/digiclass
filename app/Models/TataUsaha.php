<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TataUsaha extends Model
{
    protected $table = 'tata_usaha';
    
    protected $fillable = [
        'nama_tata_usaha',
        'nip',
        'email',
        'telepon',
        'jabatan',
        'bidang_tugas',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the user account associated with this tata usaha.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'email', 'email');
    }

    /**
     * Scope for active tata usaha
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute()
    {
        return $this->nama_tata_usaha;
    }
}