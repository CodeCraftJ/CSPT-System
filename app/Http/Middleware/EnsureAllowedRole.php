<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAllowedRole
{
    /**
     * Ensure only the allowed roles can continue through authenticated routes.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $allowedRoles = $roles !== [] ? $roles : ['admin', 'staff'];
        $user = $request->user();

        if ($user && ! in_array($user->role, $allowedRoles, true)) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'This account no longer has access to the system.',
            ]);
        }

        return $next($request);
    }
}
