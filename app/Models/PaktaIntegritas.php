<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaktaIntegritas extends Model
{
    use HasFactory;

    protected $table = 'pakta_integritas';

    protected $fillable = [
        'nama_file',
        'file_path',
        'file_type',
        'file_size',
        'deskripsi',
        'is_active',
        'uploaded_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer'
    ];

    /**
     * Get the active pakta integritas file
     */
    public static function getActiveFile()
    {
        return self::where('is_active', true)
                   ->orderBy('created_at', 'desc')
                   ->first();
    }

    /**
     * Get all active pakta integritas files
     */
    public static function getActiveFiles()
    {
        return self::where('is_active', true)
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Get file URL for download
     */
    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if file exists in storage
     */
    public function fileExists()
    {
        return Storage::exists($this->file_path);
    }

    /**
     * Delete file from storage when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($paktaIntegritas) {
            if ($paktaIntegritas->fileExists()) {
                Storage::delete($paktaIntegritas->file_path);
            }
        });
    }
}