<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. On vérifie si l'utilisateur est bien connecté
        // 2. On utilise notre nouvelle méthode pour valider qu'il est 'admin'
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return $next($request);
        }

        // Si l'utilisateur n'est pas admin, on bloque l'accès avec une erreur 403 (Interdit)
        abort(403, 'Accès réservé uniquement aux administrateurs de l\'atelier.');
    }
}