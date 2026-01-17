<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        // echo $request->change_pass;
        // exit;
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'doc' => 'required|between:11,14',
                'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
                'ddi' => 'required|digits:2',
                'ddd' => 'required|digits:2',
                'phone' => 'required|between:8,9',
                'change_pass' => '',
                'password' => 'required_if:change_pass,=,"on"|confirmed',
                'current_pass' => 'required_if:change_pass,=,"on"|string',
            ],
            [
                'name' => 'Nome completo é necessário.',
                'doc' => 'Documento é necessário.',
                'email.unique' => 'O e-mail já está cadastrado.',
                'email' => 'Um e-mail válido é necessário.',
                'ddi' => 'Selecione um país para o telefone.',
                'ddd' => 'Um DDD com dois digítos é necessário.',
                'phone' => 'Um telefone válido é necessário.',
                'password' => 'Uma senha forte e confirmação são necessárias.',
                'confirmed' => 'A confirmação de senha é necessária.',
                'current_pass' => 'Senha atual é necessária.',
            ]
        );

        $request->user()->name = $request->name;
        $request->user()->doc = $request->doc;
        $request->user()->email = $request->email;
        $request->user()->phone = $request->ddi . $request->ddd . $request->phone;
        $request->user()->password = Hash::make($request->password);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if($request->user()->isDirty('password')) {
            $request->user()->password = Hash::make($request->password);
        }
        
        if ($request->user()->isDirty()) {
            $request->user()->save();
        }

        return redirect()->back()->with('success', 'Atualizado com sucesso.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
