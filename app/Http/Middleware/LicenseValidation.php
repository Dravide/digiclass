<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\LicenseService;
use Illuminate\Support\Facades\Auth;

class LicenseValidation
{
    protected $licenseService;
    
    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip validation for license management routes (untuk admin bisa input lisensi)
        if ($this->shouldSkipValidation($request)) {
            return $next($request);
        }
        
        // Check if license is valid
        if (!$this->licenseService->isLicenseValid()) {
            // Redirect to license page or show error
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Lisensi tidak valid. Silakan hubungi administrator.',
                    'error' => 'INVALID_LICENSE'
                ], 403);
            }
            
            // For web requests, redirect to license page
            return redirect()->route('license-invalid')
                           ->with('error', 'Lisensi tidak valid. Silakan hubungi administrator.');
        }
        
        return $next($request);
    }
    
    /**
     * Determine if license validation should be skipped
     */
    protected function shouldSkipValidation(Request $request): bool
    {
        $skipRoutes = [
            'license-*',
            'login',
            'logout',
            'register'
        ];
        
        // Skip for license management routes
        foreach ($skipRoutes as $route) {
            if ($request->routeIs($route)) {
                return true;
            }
        }
        
        // Skip for admin accessing license management
        if ($request->routeIs('admin.*') && 
            Auth::check() && 
            Auth::user()->role === 'admin' &&
            str_contains($request->path(), 'license')) {
            return true;
        }
        
        return false;
    }
}
