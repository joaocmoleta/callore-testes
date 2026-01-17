<?php

namespace App\Jobs;

use App\Mail\EnvioNewsletter;
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

class EnvioNewsletterJob implements ShouldQueue
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
        // $emails = $this->buscarUsers();

        $emails = json_decode(
            '[{"email": "marliteresinha61@gmail.com", "name": "Marli Teresinha Lopes Carvalho" }, {"email": "edisonlitwin@terra.com.br", "name": "EDISON" }, {"email": "edisonlitwin@terra.com.br", "name": "EDISON" }, {"email": "edisonlitwin@terra.com.br", "name": "EDISON" }, {"email": "acfmathias@yahoo.com.br", "name": "Ana Mathias" }, {"email": "jorgehmezuvigund@gmail.com", "name": "Jorge Mattos" }, {"email": "anekael@hotmail.com", "name": "Ane Elise" }, {"email": "jairocorrealima@gmail.com", "name": "JAIRO lIMA" }, {"email": "fabricio@fhcomassetto.com.br", "name": "fabricio comassetto" }, {"email": "giovannaklemtz@outlook.com", "name": "Giovanna Klemtz" }, {"email": "interfacefloripa@gmail.com", "name": "INTERFACE" }, {"email": "contato@moleta.com.br", "name": "Jo\u00e3o Moleta" }, {"email": "marketing@moleta.com.br", "name": "Administra\u00e7\u00e3o" }, {"email": "gustavo_savio@yahoo.com.br", "name": "Gustavo S\u00e1vio" }, {"email": "otto.bede@gmail.com", "name": "Otto Bede" }, {"email": "moises.costa.maciel@gmail.com", "name": "Moises da Costa Maciel" }, {"email": "alineloreto@gmail.com", "name": "Aline Brum Loreto" }, {"email": "vendas@aquecedordetoalhas.com.br", "name": "Tatiana Melgarejo" }, {"email": "ziglioli@gmail.com", "name": "Paulo Barcellos" }, {"email": "rodrigo@weco.ind.br", "name": "Rodrigo Petry" }, {"email": "marcelo@torellybastos.com.br", "name": "Marcelo Barreto Leal" }, {"email": "marcelobc_0069@hotmail.com", "name": "Marcelo Barbosa Cardoso" }, {"email": "mazuap@hotmail.com", "name": "Maria Aparecida Zuquieri" }, {"email": "leylaruthsoares@yahoo.com.br", "name": "leyla paixao" }, {"email": "jandakon@gmail.com", "name": "Jandyra maria kondera" }, {"email": "j81godoy@gmail.com", "name": "Jo\u00e3o Victor Wallach de Godoy" }, {"email": "paula.schreiner@acad.pucrs.br", "name": "Paula Schreiner" }, {"email": "nherculano@uol.com.br", "name": "Nelsoni Souza" }, {"email": "ddorneles56@gmail.com", "name": "DELMAR HUGO LINCK DORNELES" }, {"email": "ssamuell@gmail.com", "name": "Samuel Marques" }, {"email": "marinespetroli1806@gmail.com", "name": "MARINES PETROLI" }, {"email": "philip.piontkievicz@gmail.com", "name": "Philip Piontkievicz" }, {"email": "prizzardi@hotmail.com", "name": "Priscila Rizzardi Leal" }, {"email": "gzerwes@hotmail.com", "name": "Gustavo Pereira Zerwes" }, {"email": "margarethbainy@gmail.com", "name": "Maria Margareth Bainy" }, {"email": "rosanedcarli@gmail.com", "name": "Rosane De Carli" }, {"email": "Marciorpierezan@gmail.com", "name": "Marcio rossato pierezan" }, {"email": "LARISSA@UGHINI.COM", "name": "Larissa U Da Poian" }, {"email": "mnunhofer@gmail.com", "name": "MARCELO NUNHOFER" }, {"email": "Susifracaro@hotmail.com", "name": "Susana Fraccaro" }, {"email": "liria45fag@gmail.com", "name": "Liria Costa Fagundes" }, {"email": "renanblos@gmail.com", "name": "Renan blos" }, {"email": "modabr14@gmail.com", "name": "vanderlei soares oliveira" }, {"email": "oandrelima@hotmail.com", "name": "Andr\u00e9 Ponte Lima" }, {"email": "lisianemello05@hotmail.com", "name": "Lisiane Mello" }, {"email": "edisonlitwin@terra.com.br", "name": "Edison Litwin" }, {"email": "joaocmoleta@gmail.com", "name": "Jo\u00e3o Moleta" }, {"email": "marco@wutzer.org", "name": "Marco Wutzer" }, {"email": "troepke69@gmail.com", "name": "THOMAS ROEPKE" }, {"email": "alexskow@icloud.com", "name": "Alessandra Sim\u00f5es lopes" }, {"email": "tatiane0450@yahoo.com.br", "name": "TATIANE DA SILVA GOMES" }, {"email": "tonton58@uol.com.br", "name": "Luiz A Simoes Lopes" }, {"email": "liliane.go@hotmail.com", "name": "LILIANE GUIMAR\u00c3ES OLIVEIRA" }, {"email": "cibele.yoshinaga@gmail.com", "name": "CIBELE YOSHINAGA CUNHA DE SOUZA" }, {"email": "arthurjacintho@gmail.com", "name": "Arthur Jacintho Moreno" }, {"email": "maucolombo59@yahoo.com.br", "name": "MARIA DO CARMO TIMMERS COLOMBO" }, {"email": "Liviamotta1904@gmail.com", "name": "Livia Motta" }, {"email": "priscilamizuno@yahoo.com.br", "name": "Priscila Yoko Mizuno" }, {"email": "anacfm@tjrj.jus.br", "name": "ANA MATHIAS" }, {"email": "marcelo@metalsanmartin.com.br", "name": "MARCELO MARQUES SOUZA" }, {"email": "acfmathias@yahoo.com.br", "name": "Ana Mathias" }, {"email": "paulo@raiosom.com.br", "name": "PAULO AUGUSTO SANTOS SILVA" }, {"email": "suporte@moleta.com.br", "name": "Jo\u00e3o Moleta" }, {"email": "luca.trolvi@gmail.com", "name": "Luca Trolvi" }, {"email": "giovannaklemtz@outlook.com", "name": "Giovanna Klemtz" }, {"email": "luisebsantos@gmail.com", "name": "Luis Eduardo Bastos dos Santos" }]',
            true
        );

        // $emails = [
        //     [
        //         'email' => 'contato@moleta.com.br',
        //         'name' => 'João Moleta'
        //     ],
        // ];

        // Se quiser ignorar algum e-mail
        // $index = array_search('moleta@moleta.com.br', $emails);
        // unset($emails[$index]);

        Log::info(static::class . ' linha ' . __LINE__ . ' Destinatários: ' . json_encode($emails));

        foreach ($emails as $email) {
            try {
                Mail::to($email['email'])
                    ->send(new EnvioNewsletter(['email' => $email['email'], 'name' => $email['name']]));
            } catch (\Exception $e) {
                Log::error(static::class . ' linha ' . __LINE__ . ' Falha ao enviar para ' . $email['email']);
                Log::error($e->getMessage());
            }
        }

        Log::info(static::class . ' linha ' . __LINE__ . ' Enviado e-mail para ' . count($emails) . ' leads.');
    }

    private function buscarUsers()
    {
        /*
        Listar os usuários para enviar e-mails;
        Verificar se comprou algo, colocou no carrinho ou apenas se cadastrou;
        Enviar uma newsletter com um cupom de desconto.
        */

        $pop_up_users = PopUpLeads::select('email', 'name')->get();

        $emails = [];

        foreach ($pop_up_users as $pop_up_user) {
            if (in_array($pop_up_user->email, $emails)) {
                continue;
            }
            $emails[] = [
                'email' => $pop_up_user->email,
                'name' => $pop_up_user->name
            ];
            // Log::info(static::class . ' linha '. __LINE__ . " Adicionado $pop_up_user->email na lista");
        }

        $users = User::select('email', 'name')
            ->where('publicidade', null)
            ->get();

        foreach ($users as $user) {
            if (in_array($user->email, $emails)) {
                continue;
            }
            $emails[] = [
                'email' => $user->email,
                'name' => $user->name
            ];
            // Log::info(static::class . ' linha '. __LINE__ . " Adicionado $user->email na lista");
        }

        // Log::info($emails);

        return $emails;
    }
}
