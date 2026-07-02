<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Routes d'authentification générées par Laravel UI (Connexion, Inscription, etc.)
Auth::routes();

// Route du tableau de bord de base pour tout utilisateur connecté
Route::get('/home', [HomeController::class, 'index'])->name('home');


// Groupe sécurisé : réservé uniquement aux Administrateurs
Route::middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/admin/employees', [EmployeeController::class, 'index'])->name('admin.employees.index');
    Route::get('/admin/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
    Route::put('/admin/employees/{id}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    
});


// Routes accessibles par tous les employés connectés (Admin, Styliste, Couturier)
Route::middleware(['auth'])->group(function () {
   
    // Nouvelle Route Analytique de l'Atelier (MorphoMetrics)
    Route::get('/dashboard', [OrderController::class, 'dashboard'])->name('dashboard');

    // Gestion du carnet de commandes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    
    // NOUVELLE ROUTE AJOUTÉE POUR LE JOUR 15 : Affichage de la fiche unique
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    
    // Route pour mettre à jour le statut d'une confection
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Route d'exportation PDF du passeport et de la fiche technique
    Route::get('/orders/{order}/pdf', [OrderController::class, 'downloadPDF'])->name('orders.pdf');
    // Routes pour la Gestion de contenu et Configuration globale
Route::get('/settings', [App\Http\Controllers\Admin\ConfigurationController::class, 'index'])->name('admin.settings.index');
Route::post('/settings', [App\Http\Controllers\Admin\ConfigurationController::class, 'update'])->name('admin.settings.update');

// Route pour la création directe d'un employé depuis cet espace
Route::post('/settings/employees', [App\Http\Controllers\Admin\ConfigurationController::class, 'storeEmployee'])->name('admin.settings.storeEmployee');
});