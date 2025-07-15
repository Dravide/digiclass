<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Guru extends Model
{
    protected $fillable = [
        'nama_guru',
        'nip',
        'email',
        'telepon',
        'is_wali_kelas',
        'mata_pelajaran_id'
    ];

    protected $casts = [
        'is_wali_kelas' => 'boolean'
    ];

    // Relationship dengan kelas (jika guru adalah wali kelas)
    public function kelas(): HasOne
    {
        return $this->hasOne(Kelas::class, 'guru_id');
    }

    // Relationship dengan siswa melalui kelas dan kelas_siswa pivot table
    public function siswa()
    {
        return $this->hasManyThrough(
            Siswa::class,
            KelasSiswa::class,
            'kelas_id', // Foreign key on kelas_siswa table
            'id', // Foreign key on siswa table
            'id', // Local key on guru table
            'siswa_id' // Local key on kelas_siswa table
        )->join('kelas', function($join) {
            $join->on('kelas.id', '=', 'kelas_siswa.kelas_id')
                 ->where('kelas.guru_id', '=', $this->getKey());
        })->join('tahun_pelajarans', function($join) {
            $join->on('tahun_pelajarans.id', '=', 'kelas_siswa.tahun_pelajaran_id')
                 ->where('tahun_pelajarans.is_active', '=', true);
        });
    }

    // Relationship dengan mata pelajaran
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
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
