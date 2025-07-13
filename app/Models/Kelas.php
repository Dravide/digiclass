<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kelas extends Model
{
    protected $table = 'kelas';
    
    protected $fillable = [
        'tahun_pelajaran_id',
        'nama_kelas',
        'tingkat',
        'jurusan',
        'kapasitas',
        'guru_id',
        'link_wa'
    ];

    protected $casts = [
        'kapasitas' => 'integer'
    ];

    // Relationship dengan siswa melalui KelasSiswa untuk tahun pelajaran aktif
    public function siswa(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'kelas_siswa', 'kelas_id', 'siswa_id')
            ->withPivot('tahun_pelajaran_id')
            ->whereHas('tahunPelajaran', function ($query) {
                $query->where('is_active', true);
            });
    }

    // Alternative method to get students for a specific academic year
    public function siswaByTahunPelajaran($tahunPelajaranId = null)
    {
        if (!$tahunPelajaranId) {
            $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
            $tahunPelajaranId = $activeTahunPelajaran ? $activeTahunPelajaran->id : null;
        }
        
        return $this->belongsToMany(Siswa::class, 'kelas_siswa', 'kelas_id', 'siswa_id')
            ->withPivot('tahun_pelajaran_id')
            ->wherePivot('tahun_pelajaran_id', $tahunPelajaranId);
    }

    // Relationship dengan tahun pelajaran
    public function tahunPelajaran(): BelongsTo
    {
        return $this->belongsTo(TahunPelajaran::class);
    }

    // Relationship dengan guru (wali kelas)
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    // Alias untuk wali kelas (sama dengan guru)
    public function waliKelas(): BelongsTo
    {
        return $this->guru();
    }

    // Relationship dengan kelas siswa (history siswa per tahun)
    public function kelasSiswa(): HasMany
    {
        return $this->hasMany(KelasSiswa::class);
    }


    // Scope untuk kelas pada tahun pelajaran aktif
    public function scopeActive($query)
    {
        return $query->whereHas('tahunPelajaran', function ($q) {
            $q->where('is_active', true);
        });
    }

    // Scope untuk kelas berdasarkan tahun pelajaran
    public function scopeByTahunPelajaran($query, $tahunPelajaranId)
    {
        return $query->where('tahun_pelajaran_id', $tahunPelajaranId);
    }

    // Accessor untuk jumlah siswa saat ini melalui KelasSiswa aktif
    public function getJumlahSiswaAttribute(): int
    {
        return $this->kelasSiswa()
            ->whereHas('tahunPelajaran', function ($query) {
                $query->where('is_active', true);
            })
            ->count();
    }

    // Accessor untuk sisa kapasitas
    public function getSisaKapasitasAttribute(): int
    {
        return $this->kapasitas - $this->jumlah_siswa;
    }

    // Accessor untuk nama wali kelas
    public function getNamaWaliKelasAttribute(): string
    {
        return $this->guru->nama_guru ?? '-';
    }

    // Accessor untuk ID wali kelas
    public function getWaliKelasIdAttribute(): ?int
    {
        return $this->guru_id;
    }
    
    // Accessor untuk link WhatsApp grup kelas
    public function getLinkWaGrupAttribute(): ?string
    {
        return $this->link_wa;
    }
    
    // Method untuk mengecek apakah kelas memiliki grup WhatsApp
    public function hasWhatsAppGroup(): bool
    {
        return !empty($this->link_wa);
    }
}
