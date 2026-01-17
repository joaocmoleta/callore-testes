<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        return view('dashboard.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3',
            'doc' => 'required',
            'email' => 'required|email|unique:users,email',
            'ddi' => 'required',
            'ddd' => 'required',
            'phone' => 'required',
            'city' => '',
            'password' => 'required|confirmed|min:6',
        ]);

        unset($validated['ddi']);
        unset($validated['ddd']);

        $validated['phone'] = $request->ddi . $request->ddd . $request->phone;
        $validated['password'] = Hash::make($request->password);

        User::create($validated);

        return redirect(route('users.index'))->with('success', 'Usuário adicionado com sucesso.');
    }

    public function show(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('dashboard.users.edit', compact('user', 'roles', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $valid_data = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        if ($request->password != null) {
            $valid_data['password'] = 'required|confirmed|min:6';
        }
        
        $validated = $request->validate($valid_data);

        $user->name = $request->name;
        $user->doc = $request->doc;
        $user->email = $request->email;
        $user->phone = $request->ddi . $request->ddd . $request->phone;
        $user->city = $request->city;

        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }

        $saved = $user->save();

        if ($saved) {
            return redirect()->back()->with('success', 'Usuário atualizado com sucesso.');
        } else {
            return redirect()->back()->with('error', 'Falha ao atualizar o usuáiro.');
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Deletado com sucesso.');
    }

    public function assignRole(Request $request, User $user)
    {
        if($request->role == 'super') {
            return back()->with('error', 'Super não posse ser delegada.');
        }
        if ($user->hasRole($request->role)) {
            return back()->with('error', 'Regra já atribuída.');
        }

        $user->assignRole($request->role);
        return back()->with('success', 'Regra atribuída.');
    }

    public function removeRole(User $user, Role $role)
    {
        if ($role->name == 'super') {
            return back()->with('error', 'Não é uma boa prática remover permissões do superadmin.');
        }
        if ($user->hasRole($role)) {
            $user->removeRole($role);
            return back()->with('success', 'Regra removida.');
        }

        return back()->with('error', 'Regra não existe.');
    }
}
