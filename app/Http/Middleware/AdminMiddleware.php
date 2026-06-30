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
        // 2. On vérifie si son rôle est bien 'Admin'
        if (Auth::check() && Auth::user()->role && Auth::user()->role->name === 'Admin') {
            return $next($request); // ✅ Il est Admin, on le laisse passer !
        }

        // ❌ Il n'est pas Admin ! On le bloque et on le renvoie à l'accueil avec un message d'erreur
        return redirect('/home')->with('error', 'Accès refusé ! Vous devez être Administrateur pour voir cette page.');
    }
}