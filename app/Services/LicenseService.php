<?php

namespace App\Services;

use App\Models\LicenseSetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LicenseService
{
    private const SECRET_KEY = 'DigiClass2025SecretKey';
    private const ALGORITHM_VERSION = 'v1';
    
    /**
     * Generate license key for domain
     */
    public function generateLicenseKey(string $domain, ?Carbon $expiresAt = null): string
    {
        $timestamp = now()->timestamp;
        $expiry = $expiresAt ? $expiresAt->timestamp : 0;
        
        // Create base string: domain|timestamp|expiry|version
        $baseString = $domain . '|' . $timestamp . '|' . $expiry . '|' . self::ALGORITHM_VERSION;
        
        // Create hash using secret key
        $hash = hash_hmac('sha256', $baseString, self::SECRET_KEY);
        
        // Take first 16 characters of hash
        $hashPart = substr($hash, 0, 16);
        
        // Encode timestamp and expiry to base36 for shorter string
        $timestampEncoded = base_convert($timestamp, 10, 36);
        $expiryEncoded = base_convert($expiry, 10, 36);
        
        // Create license key format: HASH-TIMESTAMP-EXPIRY-VERSION
        $licenseKey = strtoupper($hashPart . '-' . $timestampEncoded . '-' . $expiryEncoded) . '-' . self::ALGORITHM_VERSION;
        
        return $licenseKey;
    }
    
    /**
     * Validate license key for domain
     */
    public function validateLicenseKey(string $licenseKey, string $domain): array
    {
        try {
            // Parse license key
            $parts = explode('-', $licenseKey);
            
            if (count($parts) !== 4) {
                return ['valid' => false, 'message' => 'Format lisensi tidak valid'];
            }
            
            [$hashPart, $timestampEncoded, $expiryEncoded, $version] = $parts;
            
            // Check version
            if ($version !== self::ALGORITHM_VERSION) {
                return ['valid' => false, 'message' => 'Versi lisensi tidak didukung'];
            }
            
            // Decode timestamp and expiry
            $timestamp = base_convert($timestampEncoded, 36, 10);
            $expiry = base_convert($expiryEncoded, 36, 10);
            
            // Recreate base string
            $baseString = $domain . '|' . $timestamp . '|' . $expiry . '|' . $version;
            
            // Generate expected hash
            $expectedHash = hash_hmac('sha256', $baseString, self::SECRET_KEY);
            $expectedHashPart = strtoupper(substr($expectedHash, 0, 16));
            
            // Validate hash
            if ($hashPart !== $expectedHashPart) {
                return ['valid' => false, 'message' => 'Lisensi tidak valid untuk domain ini'];
            }
            
            // Check expiry
            if ($expiry > 0 && now()->timestamp > $expiry) {
                return ['valid' => false, 'message' => 'Lisensi telah kedaluwarsa'];
            }
            
            return [
                'valid' => true, 
                'message' => 'Lisensi valid',
                'generated_at' => Carbon::createFromTimestamp($timestamp),
                'expires_at' => $expiry > 0 ? Carbon::createFromTimestamp($expiry) : null
            ];
            
        } catch (\Exception $e) {
            return ['valid' => false, 'message' => 'Error validasi lisensi: ' . $e->getMessage()];
        }
    }
    
    /**
     * Check if current domain has valid license
     */
    public function isLicenseValid(): bool
    {
        $domain = $this->getCurrentDomain();
        $license = LicenseSetting::getActiveLicense($domain);
        
        if (!$license) {
            return false;
        }
        
        $validation = $this->validateLicenseKey($license->license_key, $domain);
        return $validation['valid'];
    }
    
    /**
     * Get current domain
     */
    public function getCurrentDomain(): string
    {
        $host = request()->getHost();
        
        // Remove www. prefix if exists
        if (str_starts_with($host, 'www.')) {
            $host = substr($host, 4);
        }
        
        return $host;
    }
    
    /**
     * Save license to database
     */
    public function saveLicense(string $licenseKey, array $additionalData = []): array
    {
        $domain = $this->getCurrentDomain();
        $validation = $this->validateLicenseKey($licenseKey, $domain);
        
        if (!$validation['valid']) {
            return $validation;
        }
        
        try {
            // Deactivate existing licenses for this domain
            LicenseSetting::where('domain', $domain)->update(['is_active' => false]);
            
            // Create new license
            $license = LicenseSetting::create([
                'license_key' => $licenseKey,
                'domain' => $domain,
                'app_name' => $additionalData['app_name'] ?? 'DigiClass',
                'is_active' => true,
                'expires_at' => $validation['expires_at'],
                'features' => $additionalData['features'] ?? null,
                'notes' => $additionalData['notes'] ?? null
            ]);
            
            return [
                'valid' => true,
                'message' => 'Lisensi berhasil disimpan',
                'license' => $license
            ];
            
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'message' => 'Error menyimpan lisensi: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get license info for current domain
     */
    public function getLicenseInfo(): ?array
    {
        $domain = $this->getCurrentDomain();
        $license = LicenseSetting::getActiveLicense($domain);
        
        if (!$license) {
            return null;
        }
        
        $validation = $this->validateLicenseKey($license->license_key, $domain);
        
        return [
            'license' => $license,
            'validation' => $validation,
            'domain' => $domain
        ];
    }
    
    /**
     * Generate sample license for testing
     */
    public function generateSampleLicense(): string
    {
        $domain = $this->getCurrentDomain();
        $expiresAt = now()->addYear(); // 1 year from now
        
        return $this->generateLicenseKey($domain, $expiresAt);
    }
    
    /**
     * Deactivate current license
     */
    public function deactivateLicense(): bool
    {
        try {
            $domain = $this->getCurrentDomain();
            
            LicenseSetting::where('domain', $domain)
                         ->where('is_active', true)
                         ->update(['is_active' => false]);
            
            return true;
        } catch (\Exception $e) {
            throw new \Exception('Error deaktivasi lisensi: ' . $e->getMessage());
        }
    }
}