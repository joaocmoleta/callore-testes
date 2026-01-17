<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Mail\PersonalMail;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class Contact extends Controller
{
    public function index()
    {

        $products = Product::select('*')
        ->get();

        return view('contato', compact('products'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'ddi' => 'required|digits:2',
                'ddd' => 'required|digits:2',
                'phone' => 'required|between:8,9',
                'message' => 'required',
                'agree' => 'boolean',
            ],
            [
                'name' => 'O nome é necessário.',
                'email' => 'Um e-mail válido é necessário.',
                'ddi' => 'Um DDI com dois digítos é necessário.',
                'ddd' => 'Um DDD com dois digítos é necessário.',
                'phone' => 'Um telefone válido é necessário.',
                'message' => 'Uma mensagem válida é necessária.',
                'agree' => 'Para que possamos entrar em contato usando seus dados, é preciso aceitar os termos, conforme normas da LGPD.',
            ]
        );

        Mail::to(env('MAIL_ADM'))
            ->cc([env('MAIL_MANAGER'), env('MAIL_MKT')])
            ->send(new ContactMail($validated));

        return redirect()->back()->with('success', 'Solicitação de contato realizada. Em breve entraremos em contato.');
    }

    public function personal(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'ddi' => 'required|digits:2',
                'ddd' => 'required|digits:2',
                'phone' => 'required|between:8,9',
                'model' => 'required',
                'color' => 'required',
                'cable_color' => 'required',
                'message' => 'nullable',
                'agree' => 'boolean',
            ],
            [
                'name' => 'O nome é necessário.',
                'email' => 'Um e-mail válido é necessário.',
                'ddi' => 'Um DDI com dois digítos é necessário.',
                'ddd' => 'Um DDD com dois digítos é necessário.',
                'phone' => 'Um telefone válido é necessário.',
                'model' => 'Escolha um modelo de aquecedor que deseja personalizar.',
                'color' => 'Qual é a cor pretendida para o aquecedor?',
                'cable_color' => 'Qual é a cor pretendida para o cabo do seu aquecedor?',
                'message' => 'Uma mensagem válida é necessária.',
                'agree' => 'Para que possamos entrar em contato usando seus dados, é preciso aceitar os termos, conforme normas da LGPD.',
            ]
        );

        Mail::to(env('MAIL_ADM'))
            ->cc([env('MAIL_MANAGER'), env('MAIL_MKT')])
            ->send(new PersonalMail($validated));

        return redirect(route('contact.personal.thanks'))->with('success', 'Sua solicitação de orçamento personalizado foi enviada. Em breve entraremos em contato.');
    }

    public function thanks() {
        return view('thanks');
    }

    public function revenda() {
        return view('revenda');
    }

    public function sendRevenda(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'ddi' => 'required|digits:2',
                'ddd' => 'required|digits:2',
                'phone' => 'required|between:8,9',
                'business' => ['required', 'string', 'max:255'],
                'message' => 'required',
                'agree' => 'boolean',
            ],
            [
                'name' => 'O nome é necessário.',
                'email' => 'Um e-mail válido é necessário.',
                'ddi' => 'Um DDI com dois digítos é necessário.',
                'ddd' => 'Um DDD com dois digítos é necessário.',
                'business' => 'Informe-nos o nome da sua empresa.',
                'phone' => 'Um telefone válido é necessário.',
                'message' => 'Uma mensagem válida é necessária.',
                'agree' => 'Para que possamos entrar em contato usando seus dados, é preciso aceitar os termos, conforme normas da LGPD.',
            ]
        );

        Mail::to(env('MAIL_ADM'))
            ->cc([env('MAIL_MANAGER'), env('MAIL_MKT')])
            ->send(new ContactMail($validated));

        return redirect()->back()->with('success', 'Solicitação de contato realizada. Em breve entraremos em contato.');
    }
}
