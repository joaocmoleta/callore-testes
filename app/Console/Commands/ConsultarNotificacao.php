<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ConsultarNotificacao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testes:notificacao {notificacao}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (env('PAGSEGURO_URL_WS') == '' || env('PAGSEGURO_EMAIL') == '' || env('PAGSEGURO_TOKEN') == '') {
            Log::error(static::class . ' linha ' . __LINE__ . ' Erro nas envioments utilizadas na consulta. (Webhook)');
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_URL_WS: ' . env('PAGSEGURO_URL_WS'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_EMAIL: ' . env('PAGSEGURO_EMAIL'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_TOKEN: ' . env('PAGSEGURO_TOKEN'));
            exit();
        }

        $url = env('PAGSEGURO_URL_WS') . "v3/transactions/notifications/" . $this->argument('notificacao') . "?email=" . env('PAGSEGURO_EMAIL') . "&token=" . env('PAGSEGURO_TOKEN') . "";

        Log::info(static::class . ' linha ' . __LINE__ . ' URL Notificação: ' . $url);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::warning('Erro consultar notificação webhook: ' . $err);
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' Consultar notificação webhook: ' . $response);
        }

        return Command::SUCCESS;
    }
}
