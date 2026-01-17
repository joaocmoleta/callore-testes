<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $code;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (env('PAGSEGURO_URL_WS') == '' || env('PAGSEGURO_EMAIL') == '' || env('PAGSEGURO_TOKEN') == '') {
            Log::error(static::class . ' linha ' . __LINE__ . ' Erro nas envioments utilizadas na consulta (NotificationJob).');
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_URL_WS (NotificationJob): ' . env('PAGSEGURO_URL_WS'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_EMAIL (NotificationJob): ' . env('PAGSEGURO_EMAIL'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_TOKEN (NotificationJob): ' . env('PAGSEGURO_TOKEN'));
            exit();
        }

        $url = env('PAGSEGURO_URL_WS') . "v3/transactions/notifications/" . $this->code . "?email=" . env('PAGSEGURO_EMAIL') . "&token=" . env('PAGSEGURO_TOKEN') . "";

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
            Log::warning('Erro consultar notificação webhook (NotificationJob): ' . $err);
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' Consultar notificação webhook (NotificationJob): ' . $response);
            $xml_deco = simplexml_load_string($response);

            $json_str = json_encode($xml_deco);
            $json = json_decode($json_str);

            if($json->type == 1 && $json->paymentMethod->type == 11) { // Pagamento por PIX
                $status = [
                    1 => "Aguardando pagamento",
                    2 => "Em análise",
                    3 => "Paga",
                    4 => "Disponível",
                    5 => "Em disputa",
                    6 => "Devolvida",
                    7 => "Cancelada",
                    8 => "Debitado",
                    9 => "Retenção temporária",
                ];
        
                Log::info("(NotificationJob) " . $status[$json->status]);
        
                $update = Order::where('id', $json->reference)->update(['status' => $status[$json->status]]);
                
                if($update) {
                    Log::info(static::class . ' linha ' . __LINE__ . ' Atualizado ordem (NotificationJob): ' . $json->reference);
                    if(OrderEvent::create(['order' => $json->reference, 'status' => $status[$json->status]])) {
                        Log::info(static::class . ' linha ' . __LINE__ . ' Criado evento (NotificationJob)');
                    }
                }
            }
        }
    }
}
