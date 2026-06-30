<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 🔀 L'aiguilleur dynamique des tableaux de bord
     */
    public function index()
    {
        $user = Auth::user();
        $roleName = $user->role->name; // Récupère 'Admin', 'Styliste' ou 'Couturier'

        // 1️⃣ LOGIQUE POUR L'ADMINISTRATEUR
        if ($roleName === 'Admin') {
            $stats = [
                'total_orders' => Order::count(),
                'pending_orders' => Order::where('status', 'En attente')->count(),
                'completed_orders' => Order::where('status', 'Prêt')->count(),
            ];
            return view('dashboards.admin', compact('stats'));
        }

        // 2️⃣ LOGIQUE POUR LE STYLISTE
        if ($roleName === 'Styliste') {
            // Un styliste voit les analyses récentes et les commandes en attente de style
            $recent_orders = Order::latest()->take(5)->get();
            return view('dashboards.styliste', compact('recent_orders'));
        }

        // 3️⃣ LOGIQUE POUR LE COUTURIER
        if ($roleName === 'Couturier') {
            // Le couturier ne voit QUE les tâches qui lui sont personnellement assignées
            $my_orders = Order::where('user_id', $user->id)->latest()->get();
            return view('dashboards.couturier', compact('my_orders'));
        }

        // Par sécurité, si aucun rôle ne correspond
        return view('home');
    }
}