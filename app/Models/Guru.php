<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Guru extends Model
{
    protected $fillable = [
        'nama_guru',
        'nip',
        'email',
        'telepon',
        'is_wali_kelas',
        'mata_pelajaran'
    ];

    protected $casts = [
        'is_wali_kelas' => 'boolean'
    ];

    // Relationship dengan kelas (jika guru adalah wali kelas)
    public function kelas(): HasOne
    {
        return $this->hasOne(Kelas::class, 'guru_id');
    }

    // Relationship dengan siswa melalui kelas (jika guru adalah wali kelas)
    public function siswa(): HasManyThrough
    {
        return $this->hasManyThrough(Siswa::class, Kelas::class, 'guru_id', 'kelas_id');
    }

    // Scope untuk guru yang menjadi wali kelas
    public function scopeWaliKelas($query)
    {
        return $query->where('is_wali_kelas', true);
    }

    // Scope untuk guru yang bukan wali kelas
    public function scopeBukanWaliKelas($query)
    {
        return $query->where('is_wali_kelas', false);
    }
}
