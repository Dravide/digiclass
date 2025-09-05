<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class LicenseSetting extends Model
{
    protected $fillable = [
        'license_key',
        'domain',
        'app_name',
        'is_active',
        'expires_at',
        'features',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'features' => 'array'
    ];

    /**
     * Check if license is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        
        return Carbon::now()->isAfter($this->expires_at);
    }

    /**
     * Check if license is valid for current domain
     */
    public function isValidForDomain(string $domain): bool
    {
        return $this->domain === $domain && $this->is_active && !$this->isExpired();
    }

    /**
     * Get active license for domain
     */
    public static function getActiveLicense(string $domain): ?self
    {
        return self::where('domain', $domain)
                   ->where('is_active', true)
                   ->first();
    }
}
