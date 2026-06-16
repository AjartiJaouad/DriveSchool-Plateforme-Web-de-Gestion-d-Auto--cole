<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Gérer une requête entrante.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role === $role) {
            return $next($request);
        }

        return match (auth()->user()->role) {
            'admin'    => redirect('/admin/dashboard'),
            'moniteur' => redirect('/moniteur/dashboard'),
            'candidat' => redirect('/dashboard'),
            default    => redirect('/'),
        };
    }
}
