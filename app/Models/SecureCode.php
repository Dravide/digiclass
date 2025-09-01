<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SecureCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'secure_code',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship dengan User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate secure code 50 digit yang unik
     */
    public static function generateSecureCode(): string
    {
        do {
            $secureCode = strtoupper(Str::random(50));
        } while (self::where('secure_code', $secureCode)->exists());

        return $secureCode;
    }

    /**
     * Buat secure code baru untuk user
     */
    public static function createForUser(int $userId): self
    {
        // Cek apakah user sudah memiliki secure code
        $existingCode = self::where('user_id', $userId)->first();
        
        if ($existingCode) {
            throw new \Exception('User sudah memiliki secure code aktif.');
        }
        
        return self::create([
            'user_id' => $userId,
            'secure_code' => self::generateSecureCode(),
        ]);
    }
    
    /**
     * Cek apakah user sudah memiliki secure code
     */
    public static function userHasSecureCode(int $userId): bool
    {
        return self::where('user_id', $userId)->exists();
    }
}