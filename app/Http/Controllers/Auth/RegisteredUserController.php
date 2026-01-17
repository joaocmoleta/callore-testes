<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'doc' => 'required|between:11,14',
                'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                'birth' => 'nullable|date',
                'ddi' => 'required|digits:2',
                'ddd' => 'required|digits:2',
                'phone' => 'required|between:8,9',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ],
            [
                'name' => 'O nome é necessário.',
                'doc' => 'Um documento válido é necessário.',
                'email.unique' => 'O e-mail já está cadastrado.',
                'birth' => 'Data de nascimento inválida.',
                'email' => 'Um e-mail válido é necessário.',
                'ddi' => 'Um DDI com dois digítos é necessário.',
                'ddd' => 'Um DDD com dois digítos é necessário.',
                'phone' => 'Um telefone válido é necessário.',
                'password' => 'Uma senha forte é necessária.',
                'confirmed' => 'A confirmação de senha é necessária.',
            ]
        );

        $user = User::create([
            'name' => $request->name,
            'doc' => $request->doc,
            'email' => $request->email,
            'birth' => $request->birth,
            'phone' => $request->ddi . $request->ddd . $request->phone,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // return redirect(RouteServiceProvider::HOME);
        return redirect()->intended(route('home'));
    }
}
