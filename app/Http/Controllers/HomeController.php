<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 🔀 L'aiguilleur des Tableaux de Bord (Vue simplifiée / Stats avec filtres)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $roleName = $user->role ? $user->role->name : null;

        // 1️⃣ LOGIQUE POUR L'ADMINISTRATEUR
        if ($roleName === 'Admin') {
            $period = $request->get('period', 'all');
            $query = Order::query();

            switch ($period) {
                case 'week': $query->where('created_at', '>=', Carbon::now()->startOfWeek()); break;
                case 'month': $query->where('created_at', '>=', Carbon::now()->startOfMonth()); break;
                case 'quarter': $query->where('created_at', '>=', Carbon::now()->subMonths(3)); break;
                case 'year': $query->where('created_at', '>=', Carbon::now()->startOfYear()); break;
            }

            $stats = [
                'total_orders'     => $query->count(),
                'pending_orders'   => $query->clone()->where('status', 'En attente')->count(),
                'completed_orders' => $query->clone()->where('status', 'Prêt')->count(),
            ];

            return view('dashboards.admin', compact('stats'));
        }

        // 2️⃣ LOGIQUE POUR LE STYLISTE
        if ($roleName === 'Styliste') {
            $recent_orders = Order::latest()->take(5)->get();
            return view('dashboards.styliste', compact('recent_orders'));
        }

        // 3️⃣ LOGIQUE POUR LA COUTURIÈRE / CLIENTE (Avec filtres temporels privés)
        if ($roleName === 'Couturier') {
            $period = $request->get('period', 'all');
            
            // Sécurité stricte : On isole d'abord ses propres commandes
            $query = Order::where('user_id', $user->id);

            // Application du filtre temporel choisi sur son périmètre
            switch ($period) {
                case 'week': $query->where('created_at', '>=', Carbon::now()->startOfWeek()); break;
                case 'month': $query->where('created_at', '>=', Carbon::now()->startOfMonth()); break;
                case 'quarter': $query->where('created_at', '>=', Carbon::now()->subMonths(3)); break;
                case 'year': $query->where('created_at', '>=', Carbon::now()->startOfYear()); break;
            }

            $stats = [
                'total_orders'     => $query->count(),
                'pending_orders'   => $query->clone()->where('status', 'En attente')->count(),
                'completed_orders' => $query->clone()->where('status', 'Prêt')->count(),
            ];
            
            return view('dashboards.couturier_dashboard', compact('stats'));
        }

        return view('home');
    }

    /**
   /**
     * 📊 Espace Rapport & Tâches (Dédié à la vue complète de l'atelier)
     */
    public function rapport(Request $request)
    {
        $user = Auth::user();
        $roleName = $user->role ? $user->role->name : null;

        // Si c'est une couturière, on lui sort la liste de ses confections filtrée par période
        if ($roleName === 'Couturier') {
            $period = $request->get('period', 'all');
            
            // Sécurité stricte : On isole ses commandes
            $query = Order::where('user_id', $user->id);

            // Application du filtre temporel choisi
            switch ($period) {
                case 'week': $query->where('created_at', '>=', Carbon::now()->startOfWeek()); break;
                case 'month': $query->where('created_at', '>=', Carbon::now()->startOfMonth()); break;
                case 'quarter': $query->where('created_at', '>=', Carbon::now()->subMonths(3)); break;
                case 'year': $query->where('created_at', '>=', Carbon::now()->startOfYear()); break;
            }

            // Récupération des commandes filtrées
            $my_orders = $query->latest()->get();
            
            // Cette vue correspond à l'interface "Mon Atelier de Confection"
            return view('dashboards.couturier', compact('my_orders'));
        }

        // Redirection de sécurité si un autre rôle tente d'y accéder sans droit
        return redirect()->route('home')->with('error', 'Accès non autorisé.');
    }
}