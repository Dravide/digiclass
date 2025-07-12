<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Siswa extends Model
{
    protected $table = 'siswa';
    
    protected $fillable = [
        'nama_siswa',
        'jk',
        'nisn',
        'nis',
        'tahun_pelajaran_id'
    ];

    protected $casts = [
        'jk' => 'string'
    ];

    // Relationship dengan kelas saat ini melalui KelasSiswa aktif
    public function kelas(): BelongsTo
    {
        // Get kelas from active KelasSiswa record
        $activeKelasSiswa = $this->kelasSiswa()
            ->whereHas('tahunPelajaran', function ($query) {
                $query->where('is_active', true);
            })
            ->first();
            
        if ($activeKelasSiswa) {
            return $this->belongsTo(Kelas::class, null, 'id')->where('id', $activeKelasSiswa->kelas_id);
        }
        
        // Fallback to empty relationship if no active kelas found
        return $this->belongsTo(Kelas::class, null, 'id')->whereRaw('1 = 0');
    }
    
    // Method to get current kelas_id from active KelasSiswa
    public function getCurrentKelasIdAttribute(): ?int
    {
        $activeKelasSiswa = $this->kelasSiswa()
            ->whereHas('tahunPelajaran', function ($query) {
                $query->where('is_active', true);
            })
            ->first();
            
        return $activeKelasSiswa ? $activeKelasSiswa->kelas_id : null;
    }
    
    // Method to get current kelas object
    public function getCurrentKelas()
    {
        $kelasId = $this->current_kelas_id;
        return $kelasId ? Kelas::find($kelasId) : null;
    }

    // Relationship dengan guru (wali kelas) melalui kelas aktif
    public function guru()
    {
        $currentKelas = $this->getCurrentKelas();
        return $currentKelas ? $currentKelas->guru() : null;
    }

    // Method to get current guru object
    public function getCurrentGuru()
    {
        $currentKelas = $this->getCurrentKelas();
        return $currentKelas ? $currentKelas->guru : null;
    }

    // Relationship dengan tahun pelajaran
    public function tahunPelajaran(): BelongsTo
    {
        return $this->belongsTo(TahunPelajaran::class, 'tahun_pelajaran_id');
    }

    // Relationship dengan perpustakaan
    public function perpustakaan(): HasOne
    {
        return $this->hasOne(Perpustakaan::class);
    }

    // Relationship dengan kelas siswa (history kelas per tahun)
    public function kelasSiswa(): HasMany
    {
        return $this->hasMany(KelasSiswa::class);
    }

    // Accessor untuk dapat mengakses link WA berdasarkan status perpustakaan dan ketersediaan grup kelas
    public function getCanAccessLinkWaAttribute(): bool
    {
        $hasLibraryAccess = $this->perpustakaan && $this->perpustakaan->terpenuhi;
        $currentKelas = $this->getCurrentKelas();
        $hasWhatsAppGroup = $currentKelas && $currentKelas->hasWhatsAppGroup();
        
        return $hasLibraryAccess && $hasWhatsAppGroup;
    }
    
    // Method untuk mendapatkan link WhatsApp grup kelas
    public function getLinkWaGrupKelas(): ?string
    {
        $currentKelas = $this->getCurrentKelas();
        return $currentKelas ? $currentKelas->link_wa : null;
    }

    // Accessor untuk mendapatkan status perpustakaan
    public function getStatusPerpustakaanAttribute(): string
    {
        if ($this->perpustakaan && $this->perpustakaan->terpenuhi) {
            return 'aktif';
        }
        return 'tidak_aktif';
    }

    // Scope untuk siswa dengan perpustakaan aktif
    public function scopePerpustakaanAktif($query)
    {
        return $query->whereHas('perpustakaan', function ($q) {
            $q->where('terpenuhi', true);
        });
    }

    // Scope untuk siswa dengan perpustakaan tidak aktif
    public function scopePerpustakaanTidakAktif($query)
    {
        return $query->whereDoesntHave('perpustakaan')
                    ->orWhereHas('perpustakaan', function ($q) {
                        $q->where('terpenuhi', false);
                    });
    }

    // Scope untuk siswa berdasarkan tahun pelajaran aktif
    public function scopeActive($query)
    {
        return $query->whereHas('tahunPelajaran', function ($q) {
            $q->where('is_active', true);
        });
    }

    // Scope untuk siswa berdasarkan tahun pelajaran tertentu
    public function scopeByTahunPelajaran($query, $tahunPelajaranId)
    {
        return $query->where('tahun_pelajaran_id', $tahunPelajaranId);
    }
}
