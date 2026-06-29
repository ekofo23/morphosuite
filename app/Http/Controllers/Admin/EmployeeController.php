<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;


class EmployeeController extends Controller
{
    /**
     * Afficher la liste des employés
     */
    public function index()
    {
        // On récupère tous les utilisateurs avec leur rôle associé
        $employees = User::with('role')->get();

        // On envoie ces données à une vue qu'on va créer juste après
        return view('admin.employees.index', compact('employees'));
    }
    // N'oublie pas d'ajouter cet import tout en haut du fichier s'il n'y est pas :
// use App\Models\Role;

/**
 * Afficher le formulaire de modification
 */
public function edit($id)
{
    $employee = User::findOrFail($id);
    $roles = Role::all(); 

    return view('admin.employees.edit', compact('employee', 'roles'));
}
/**
 * Enregistrer le changement de rôle
 */
public function update(Request $request, $id)
{
    $request->validate([
        'role_id' => 'required|exists:roles,id',
    ]);

    $employee = User::findOrFail($id);
    $employee->update([
        'role_id' => $request->role_id,
    ]);

    return redirect()->route('admin.employees.index')->with('success', 'Le rôle de l\'employé a bien été mis à jour !');
}
}