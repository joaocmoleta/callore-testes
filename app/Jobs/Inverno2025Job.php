<?php

namespace App\Jobs;

use App\Mail\Inverno2025;
use App\Models\PopUpLeads;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Inverno2025Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emails = $this->buscarUsers();

        // $emails = [
        //     'joaocmoleta@gmail.com',
        //     'contato@moleta.com.br'
        // ];

        // Se quiser ignorar algum e-mail
        // $index = array_search('moleta@moleta.com.br', $emails);
        // unset($emails[$index]);
        // $index = array_search('leylaruthsoares@yahoo.com.br', $emails);
        // unset($emails[$index]);
        // $index = array_search('contato@moleta.com.br', $emails);
        // unset($emails[$index]);

        Log::info(static::class . ' linha ' . __LINE__ . ' Destinatários: ' . json_encode($emails));

        // Mail::bcc($emails)
        //     ->send(new Inverno2025());

        foreach ($emails as $email) {
            Mail::to($email)
                ->send(new Inverno2025($email));
        }

        Log::info(static::class . ' linha ' . __LINE__ . ' Envio finalizado.');
    }

    private function buscarUsers()
    {
        /*
        Listar os usuários para enviar e-mails;
        Verificar se comprou algo, colocou no carrinho ou apenas se cadastrou;
        Enviar uma newsletter com um cupom de desconto.
        */

        $pop_up_users = PopUpLeads::get();

        $emails = [];

        foreach ($pop_up_users as $pop_up_user) {
            if (in_array($pop_up_user->email, $emails)) {
                continue;
            }
            $emails[] = $pop_up_user->email;
            // Log::info(static::class . ' linha '. __LINE__ . " Adicionado $pop_up_user->email na lista");
        }

        $users = User::get();

        foreach ($users as $user) {
            if (in_array($user->email, $emails)) {
                continue;
            }
            $emails[] = $user->email;
            // Log::info(static::class . ' linha '. __LINE__ . " Adicionado $user->email na lista");
        }

        return $emails;
    }
}
