<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index() {
        $permissions = Permission::all();
        return view('dashboard.permissions.index', compact('permissions'));
    }

    public function create() {
        return view('dashboard.permissions.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|min:3'
        ]);

        $has = Permission::where('name', $request->name)->first();
        
        if($has) {
            return redirect()->back()->with('error', 'Já existe uma permissão com esse nome.')->withInput();
        }

        $permission = Permission::create($validated);
        
        $role = Role::where('name', 'super')->first();
        $role->givePermissionTo($permission);

        return redirect(route('permissions.index'))->with('success', 'Adicionada com sucesso.');
    }

    public function edit(Permission $permission) {
        return view('dashboard.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission) {
        $validated = $request->validate([
            'name' => 'required|min:3|unique:permissions,name,' . $permission->id
        ]);
        $permission->name = $request->name;
        $permission->save();

        return back()->with('success', 'Alterações salvas.');
    }

    public function destroy(Permission $permission) {
        $permission->delete();
        return back()->with('success', 'Deletado com sucesso.');
    }
}
