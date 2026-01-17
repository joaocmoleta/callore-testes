<?php

namespace App\Http\Controllers;

use App\Jobs\NewLead;
use App\Models\PopUpLeads;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class PopUpLeadsController extends Controller
{
    public function cadastrar(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|min:3',
                'phone' => 'required|min:10',
                'email' => 'required|email'
            ],
            [
                'name' => 'Insira um nome válido de no mínimo 3 letras.',
                'phone' => 'Insira um telefone válido.',
                'email' => 'Insira um e-mail válido.'
            ]
        );

        if (PopUpLeads::create($validated)) {
            // NewLead::dispatch($validated);
            return redirect()->back()->with('success', 'Cupom gerado com sucesso.');
        }

        return redirect()->back()->with('error', 'Houve algum erro.');
    }

    public function pre_cadastrar_users()
    {
        /*
        Listar os usuários para enviar e-mails;
        Verificar se comprou algo, colocou no carrinho ou apenas se cadastrou;
        Enviar uma newsletter com um cupom de desconto.
        */

        $sended = [
            'moleta@moleta.com.br',
            'contato@moleta.com.br',
            'leylaruthsoares@yahoo.com.br',
            'rodrigo@weco.ind.br',
            'vendas@aquecedordetoalhas.com.br',
            'marketing@moleta.com.br',
            'joaocmoleta@gmail.com',
        ];

        $ignored = count($sended);
        
        $pop_up_users = PopUpLeads::get();

        foreach ($pop_up_users as $pop_up_user) {
            if(in_array($pop_up_user->email, $sended)) {
                continue;
            }
            $sended[] = $pop_up_user->email;
            echo "<p>$pop_up_user->name ($pop_up_user->email)</p>";
        }

        $users = User::get();

        foreach($users as $user) {
            if(in_array($user->email, $sended)) {
                continue;
            }
            $sended[] = $user->email;
            echo "<p>$user->name ($user->email)</p>";
        }

        $final = count($sended) - $ignored;

        echo "<p>Total de $final e-mails";
    }

    // public function remove_duplicates() {
    // // public function pre_cadastrar_users() {
    //     $pop_up_users = PopUpLeads::get();

    //     $pop_up_users_new = [
    //         'name' => '',
    //         'phone' => '',
    //         'email' => '',
    //     ];

    //     foreach($pop_up_users as $pop_up_user) {
    //         if(in_array($pop_up_user->email, $pop_up_users_new['email'])) {
    //             echo "<p>Já existe e-mail</p>";
    //         } else {
    //             $pop_up_users_new[] = [
    //                 'name' => $pop_up_user->name,
    //                 'phone' => $pop_up_user->phone,
    //                 'email' => $pop_up_user->email,
    //             ];
    //             echo "<p>Ainda não existe e-mail</p>";
    //         }
    //     }
    // }
}
