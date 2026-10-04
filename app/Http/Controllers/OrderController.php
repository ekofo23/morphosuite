<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Mannequin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Protéger les commandes : l'utilisateur doit être connecté
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des commandes / fiches de mesures (Carnet de Commandes)
     */
    public function index(Request $request)
    {
        // 1. Initialisation de la requête selon le rôle
        if (Auth::user()->role->name === 'Admin') {
            $query = Order::with('user');
        } else {
            $query = Order::where('user_id', Auth::id());
        }

        // 🔍 BARRE DE RECHERCHE : Par nom de client
        if ($request->filled('search')) {
            $query->where('client_name', 'like', '%' . $request->search . '%');
        }

        // 📅 FILTRES TEMPORELS (Basés sur le paramètre 'period')
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('created_at', now()->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'quarter':
                    $query->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()]);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        // 2. On applique le tri et récupère les données
        $orders = $query->latest()->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Page "Mon Atelier de Confection" (Affiche SEULEMENT les commandes au statut 'Prêt')
     */
    public function confections(Request $request)
    {
        // 🎯 Restriction stricte : Seules les commandes au statut 'Prêt' assignées au couturier connecté
        $query = Order::where('user_id', Auth::id())
                      ->where('status', 'Prêt');

        // 📅 Application des mêmes filtres temporels sur l'atelier
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('created_at', now()->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'quarter':
                    $query->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()]);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        $my_orders = $query->latest()->get();

        return view('couturier.rapport', compact('my_orders'));
    }

    /**
     * Afficher le formulaire de saisie des mesures
     */
    public function create()
    {
        $employees = User::all();
        return view('orders.create', compact('employees'));
    }

    /**
     * Enregistrer la commande et déclencher MorphoCore
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:20',
            'shoulder_measurement' => 'required|integer|min:10',
            'chest_measurement' => 'required|integer|min:10',
            'waist_measurement' => 'required|integer|min:10',
            'hip_measurement' => 'required|integer|min:10',
            'buste_length' => 'required|string|in:court,normal,long',
            'posture_type' => 'required|string|in:standard,cambrée,normale,voûtée',
            'arm_length' => 'nullable|integer',
            'total_length' => 'nullable|integer',
        ]);

        // MÉCANISME ANTI-DOUBLON
        $existingOrder = Order::where('client_name', $request->client_name)
            ->where('shoulder_measurement', $request->shoulder_measurement)
            ->where('chest_measurement', $request->chest_measurement)
            ->where('waist_measurement', $request->waist_measurement)
            ->where('hip_measurement', $request->hip_measurement)
            ->where('buste_length', $request->buste_length)
            ->where('posture_type', $request->posture_type)
            ->first();

        if ($existingOrder) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['client_name' => 'Ces mesures sont déjà enregistrées pour cette cliente dans notre historique.']);
        }

        // Création de la commande
        $order = Order::create([
            'client_name' => $request->client_name,
            'client_phone' => $request->client_phone,
            'shoulder_measurement' => $request->shoulder_measurement,
            'chest_measurement' => $request->chest_measurement,
            'waist_measurement' => $request->waist_measurement,
            'hip_measurement' => $request->hip_measurement,
            'buste_length' => $request->buste_length,
            'posture_type' => $request->posture_type,
            'arm_length' => $request->arm_length,
            'total_length' => $request->total_length,
            'user_id' => $request->user_id ?? Auth::id(),
        ]);

        // Liaison Mannequin 3D
        Mannequin::create([
            'order_id'             => $order->id,
            'profile_name'         => 'Mannequin de ' . $order->client_name,
            'shoulder_measurement' => $order->shoulder_measurement,
            'chest_measurement'    => $order->chest_measurement,
            'waist_measurement'    => $order->waist_measurement,
            'hip_measurement'      => $order->hip_measurement,
            'dominant_morphology'  => $order->dominant_morphology ?? 'H',
        ]);

        return redirect()->route('orders.index')->with('success', 'Fiche client, analyse MorphoCore et modèle 3D enregistrés avec succès !');
    }

    /**
     * Mettre à jour le statut de fabrication d'une confection
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:En attente,En coupe,En couture,Prêt',
        ]);

        $order = Order::findOrFail($id);
        
        if (Auth::user()->role->name === 'Couturier' && $order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier cette confection.');
        }

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Le statut de la confection a été mis à jour avec succès !');
    }

    /**
     * Génère et télécharge le PDF
     */
    public function downloadPDF($id)
    {
        $order = Order::with('user')->findOrFail($id);
        $conseils = \App\Services\StyleAdvisorService::generateAdvisor($order);

        $pdf = Pdf::loadView('orders.pdf', compact('order', 'conseils'));
        $pdf->setPaper('a4', 'portrait');

        $filename = 'fiche_' . Str::slug($order->client_name, '_') . '_order_' . $order->id . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Génère les statistiques globales avec filtres et selon le rôle connecté
     */
    public function dashboard(Request $request)
    {
        // 1. Initialisation des requêtes selon le rôle (Admin voit tout, Couturier voit ses données uniquement)
        if (Auth::user()->role->name === 'Admin') {
            $orderQuery = Order::query();
            $statusQuery = Order::select('status', \DB::raw('count(*) as total'))->groupBy('status');
            $morphologyQuery = Order::select('dominant_morphology', \DB::raw('count(*) as total'))->groupBy('dominant_morphology');
        } else {
            $orderQuery = Order::where('user_id', Auth::id());
            $statusQuery = Order::where('user_id', Auth::id())->select('status', \DB::raw('count(*) as total'))->groupBy('status');
            $morphologyQuery = Order::where('user_id', Auth::id())->select('dominant_morphology', \DB::raw('count(*) as total'))->groupBy('dominant_morphology');
        }

        // 📅 APPLICATION DU FILTRE TEMPOREL SUR TOUTES LES STATISTIQUES (Boutons Journée, Semaine...)
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $orderQuery->whereDate('created_at', now()->toDateString());
                    $statusQuery->whereDate('created_at', now()->toDateString());
                    $morphologyQuery->whereDate('created_at', now()->toDateString());
                    break;
                case 'week':
                    $range = [now()->startOfWeek(), now()->endOfWeek()];
                    $orderQuery->whereBetween('created_at', $range);
                    $statusQuery->whereBetween('created_at', $range);
                    $morphologyQuery->whereBetween('created_at', $range);
                    break;
                case 'month':
                    $orderQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    $statusQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    $morphologyQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
                case 'quarter':
                    $range = [now()->startOfQuarter(), now()->endOfQuarter()];
                    $orderQuery->whereBetween('created_at', $range);
                    $statusQuery->whereBetween('created_at', $range);
                    $morphologyQuery->whereBetween('created_at', $range);
                    break;
                case 'year':
                    $orderQuery->whereYear('created_at', now()->year);
                    $statusQuery->whereYear('created_at', now()->year);
                    $morphologyQuery->whereYear('created_at', now()->year);
                    break;
            }
        }

        // 2. Récupération des totaux calculés et filtrés
        $totalOrders = $orderQuery->count();
        
        $statusCounts = $statusQuery->pluck('total', 'status')->toArray();

        $statuses = ['En attente' => 0, 'En coupe' => 0, 'En couture' => 0, 'Prêt' => 0];
        foreach ($statuses as $key => $value) {
            $statuses[$key] = $statusCounts[$key] ?? 0;
        }

        $morphologyCounts = $morphologyQuery->orderBy('total', 'desc')->get();

        // Charge de l'atelier (les commandes qui ne sont pas encore prêtes)
        $artisanLoads = \App\Models\User::withCount(['orders' => function($query) {
                            $query->where('status', '!=', 'Prêt');
                        }])->get();

        return view('dashboard', compact('totalOrders', 'statuses', 'morphologyCounts', 'artisanLoads'));
    }

    /**
     * Afficher le détail d'une fiche client
     */
    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);
        $employees = \App\Models\User::all(); 
        return view('orders.show', compact('order', 'employees'));
    }
}