<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Redirect based on user role
            if ($user->hasRole('admin')) {
                return redirect()->route('dashboard');
            } elseif ($user->hasRole('guru')) {
                return redirect()->route('dashboard');
            } elseif ($user->hasRole('siswa')) {
                return redirect()->route('dashboard');
            } elseif ($user->hasRole('tata_usaha')) {
                return redirect()->route('dashboard');
            } elseif ($user->hasRole('bk')) {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}