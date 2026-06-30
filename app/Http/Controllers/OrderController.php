<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Afficher la liste des commandes / fiches de mesures
     */
    public function index()
    {
        // Si c'est l'admin, il voit tout. Si c'est un employé, il ne voit que ses commandes assignées.
        if (Auth::user()->role->name === 'Admin') {
            $orders = Order::with('user')->latest()->get();
        } else {
            $orders = Order::where('user_id', Auth::id())->latest()->get();
        }

        return view('orders.index', compact('orders'));
    }

    /**
     * Afficher le formulaire de saisie des mesures
     */
    public function create()
    {
        // On récupère la liste des couturiers/stylistes pour pouvoir leur assigner la commande (utile pour l'admin)
        $employees = User::all();
        return view('orders.create', compact('employees'));
    }

    /**
     * Enregistrer la commande et déclencher MorphoCore (Avec historique & anti-doublon)
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

        // 🔍 MÉCANISME ANTI-DOUBLON INTELLIGENT
        // Vérification si une analyse strictement identique existe déjà pour cette cliente
        $existingOrder = Order::where('client_name', $request->client_name)
            ->where('shoulder_measurement', $request->shoulder_measurement)
            ->where('chest_measurement', $request->chest_measurement)
            ->where('waist_measurement', $request->waist_measurement)
            ->where('hip_measurement', $request->hip_measurement)
            ->where('buste_length', $request->buste_length)
            ->where('posture_type', $request->posture_type)
            ->first();

        if ($existingOrder) {
            // Si doublon parfait trouvé, on bloque l'écriture et on renvoie le message d'erreur
            return redirect()->back()
                ->withInput()
                ->withErrors(['client_name' => 'Ces mesures sont déjà enregistrées pour cette cliente dans notre historique.']);
        }

        // 🚀 Si au moins un élément diffère, on crée une nouvelle ligne d'historique (horodatée via created_at)
        // L'événement static::saving dans Order.php interceptera ceci pour calculer MorphoCore
        Order::create([
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
            'user_id' => $request->user_id ?? Auth::id(), // Assigné à l'employé choisi ou à soi-même
        ]);

        return redirect()->route('orders.index')->with('success', 'Fiche client et analyse MorphoCore enregistrées avec succès !');
    }

    /**
     * 🪡 Mettre à jour le statut de fabrication d'une confection (Nouveauté Jour 7)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:En attente,En coupe,En couture,Prêt',
        ]);

        $order = Order::findOrFail($id);
        
        // Sécurité : Un couturier ne peut modifier que les confections qui lui sont personnellement assignées
        if (Auth::user()->role->name === 'Couturier' && $order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier cette confection.');
        }

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Le statut de la confection a été mis à jour avec succès !');
    }
}