<?php

namespace App\Jobs;

use App\Models\PagarmePedidos;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CancelChargePagarme implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order_id;
    // private $charge_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->order_id = $data;
        // $this->charge_id = $data[1];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Obter a charge id
        $pagarme_pedidos = PagarmePedidos::select('charge_id', 'paid_amount')->where('order', $this->order_id)->first();

        if (env('PAGARME_URL') == '' || env('PAGARME_SK') == '') {
            Log::error(static::class . ' linha ' . __LINE__ . ' Erro nas envioments utilizadas no pagamento.');
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGARME_DEBUG: ' . env('PAGARME_DEBUG'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGARME_URL: ' . env('PAGARME_URL'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGARME_SK: ' . env('PAGARME_SK'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGARME_SK_PWD: ' . env('PAGARME_SK_PWD'));
            exit();
        }

        Log::info(static::class . ' Line ' . __LINE__ . " Iniciado preparo dos dados para estorno PagarMe para ordem " . $this->order_id . '.');

        $fields = [
            'amount' => $pagarme_pedidos->paid_amount
        ];

        if (env('PAGSEGURO_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init(env('PAGARME_URL_CHARGES') . '/' . $pagarme_pedidos->charge_id);

        if (env('PAGSEGURO_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        Log::info(static::class . ' Line ' . __LINE__ . " Payload: " . json_encode($fields));

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode(env('PAGARME_SK') . ':' . env('PAGARME_SK_PWD')),
            'Content-Type: application/json',
        ]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);

        Log::info(static::class . ' linha ' . __LINE__ . ' Iniciando transmissão dos dados...');

        $response = curl_exec($curl);

        if (env('PAGSEGURO_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();

            Log::info(static::class . ' linha ' . __LINE__ . ' Debug: ' . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo: ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error: ' . curl_error($curl));
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' Retorno pagarme: ' . $response);
        }

        Log::info(static::class . ' Linha ' . __LINE__ . "Finalizado envio de ordem de cancelamento PagarMe para ordem " . $this->order_id . ". Com ou sem erros.");
    }
}
