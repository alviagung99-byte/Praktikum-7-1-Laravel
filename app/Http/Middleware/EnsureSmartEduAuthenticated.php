<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSmartEduAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = session('smartedu_user');

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = strtolower((string) ($user['role'] ?? ''));
        $allowedRoles = array_map(
            fn ($role) => strtolower(str_replace(' ', '-', trim($role))),
            $roles
        );

        if (!empty($allowedRoles) && !in_array($userRole, $allowedRoles, true)) {
            $redirectMap = [
                'staff-tu' => 'staff-tu.dashboard',
                'guru' => 'guru.dashboard',
                'siswa' => 'siswa.dashboard',
                'orang-tua' => 'orang-tua.dashboard',
            ];

            $targetRoute = $redirectMap[$userRole] ?? 'login';

            return redirect()->route($targetRoute)->with('error', 'Akses tidak diizinkan untuk role ini.');
        }

        return $next($request);
    }
}
