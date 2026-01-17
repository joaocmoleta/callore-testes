<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

use Spatie\Permission\Models\Permission;
// use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index() {
        $roles = Role::all();
        return view('dashboard.roles.index', compact('roles'));
    }

    public function create() {
        $permissions = Permission::all();
        return view('dashboard.roles.create', compact('permissions'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|min:3|unique:roles'
        ]);
        Role::create($validated);

        return redirect(route('roles.index'))->with('success', 'Adicionado com sucesso.');
    }

    public function show(Role $role) {
        return view('dashboard.roles.edit', compact('role'));
    }

    public function edit(Role $role) {
        $permissions = Permission::all();
        return view('dashboard.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role) {
        if($request->name == 'super' || $request->name == 'admin') {
            return redirect()->back()->with('error', 'Impossível alterar as regras super e admin.');
        }

        $validated = $request->validate([
            'name' => 'required|min:3'
        ]);
        $role->name = $request->name;
        $role->save();
        return back()->with('success', 'Alterações salvas.');
    }

    public function destroy(Role $role) {
        $role->delete();
        return back()->with('success', 'Deletado com sucesso.');
    }

    public function givePermission(Request $request, Role $role) {
        if($role->hasPermissionTo($request->permission)) {
            return back()->with('error', 'Permissão já existe.');
        }
        $role->givePermissionTo($request->permission);
        return back()->with('success', 'Permissão concedida.');
    }

    public function revokePermission(Role $role, Permission $permission) {
        if($role->name == 'super') {
            return back()->with('error', 'Não é uma boa prática remover permissões do superadmin.');
        }
        if($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
            return back()->with('success', 'Permissão revogada.');
        }
        return back()->with('error', 'Permissão não encontrada.');
    }
}
