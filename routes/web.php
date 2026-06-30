<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\HomeController; // 👈 Ajouté pour la route /home
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// 🔐 Routes d'authentification générées par Laravel UI (Connexion, Inscription, etc.)
Auth::routes();

// 🏠 Route du tableau de bord de base pour tout utilisateur connecté
Route::get('/home', [HomeController::class, 'index'])->name('home');


// 🔒 Groupe sécurisé : réservé uniquement aux Administrateurs
Route::middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/admin/employees', [EmployeeController::class, 'index'])->name('admin.employees.index');
    Route::get('/admin/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
    Route::put('/admin/employees/{id}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    
});


// 👗 Routes accessibles par tous les employés connectés (Admin, Styliste, Couturier)
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    // 🔄 Route pour mettre à jour le statut d'une confection (accessible par les couturiers/admins)
    Route::patch('/orders/{id}/status',
 [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});