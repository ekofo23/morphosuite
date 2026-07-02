<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                
                // 1. Récupérer l'utilisateur connecté et son rôle
                $user = Auth::guard($guard)->user();
                
                // On vérifie que la relation 'role' existe sur ton modèle User
                if ($user && $user->role) {
                    $roleName = $user->role->name;

                    // 2. Aiguillage dynamique selon le nom exact du rôle
                    switch ($roleName) {
                        case 'Administrateur':
                            return redirect()->route('home'); // Tableau de bord global
                        
                        case 'Styliste':
                            return redirect()->route('styliste.index'); // Ton espace styliste
                        
                        case 'Couturier':
                            return redirect()->route('couturier.index'); // Ton espace couturier
                    }
                }

                // Repli par défaut si le rôle n'est pas reconnu
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}