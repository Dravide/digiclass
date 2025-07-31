<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surat';

    protected $fillable = [
        'nomor_surat',
        'jenis_surat',
        'perihal',
        'isi_surat',
        'penerima',
        'jabatan_penerima',
        'tanggal_surat',
        'signature_data',
        'qr_code_path',
        'status',
        'signed_at',
        'validated_at',
        'created_by'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'signed_at' => 'datetime',
        'validated_at' => 'datetime'
    ];

    /**
     * Get the user who created this surat
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate nomor surat otomatis
     */
    public static function generateNomorSurat($jenisSurat)
    {
        $tahun = date('Y');
        $bulan = date('m');
        
        // Format: 001/JENIS/SMPN1CPN/MM/YYYY
        $lastSurat = self::where('jenis_surat', $jenisSurat)
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->orderBy('id', 'desc')
            ->first();
        
        $urutan = 1;
        if ($lastSurat) {
            $nomorParts = explode('/', $lastSurat->nomor_surat);
            $urutan = intval($nomorParts[0]) + 1;
        }
        
        return sprintf('%03d/%s/SMPN1CPN/%s/%s', $urutan, strtoupper($jenisSurat), $bulan, $tahun);
    }

    /**
     * Check if surat is signed
     */
    public function isSigned()
    {
        return $this->status === 'signed' || $this->status === 'validated';
    }

    /**
     * Check if surat is validated
     */
    public function isValidated()
    {
        return $this->status === 'validated';
    }
}