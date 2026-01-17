<?php

namespace App\Console\Commands;

use App\Jobs\SendTotalFiles;
use App\Models\Encomenda;
use App\Models\Order;
use App\Models\OrderEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SolicitarColetaManual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'solicitar:coleta_manual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Solicitar coleta manualmente';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $payload = '{"remetenteId":"44432","cnpj":"92783182000132","encomendas":[{"servicoTipo":1,"entregaTipo":"0","peso":10.5,"volumes":"1","condFrete":"CIF","pedido":"pedido-100","natureza":"Venda Merc. Cons. N\u00e3o Co","icmsIsencao":1,"destinatario":{"nome":"THOMAS ROEPKE","cpfCnpj":"57623767968","endereco":{"logradouro":"RUA BOLIVIA","numero":"1219","complemento":"LOJA 1 MB INFORMATICA","pontoReferencia":"","bairro":"CENTRO","cidade":"Timb\u00f3","estado":"SC","cep":"89120000"}},"docFiscal":{"nfe":[{"nfeNumero":"15274","nfeSerie":"1","nfeData":"2024-08-27","nfeValTotal":"2328.40","nfeValProd":"2328.40","nfeChave":"43240892783182000132550010000152741803824427"}]}}]}';
        $order_id = 100;

        $response = $this->curlExecute($payload);

        if ($response['error']) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error: ' . $response['error']);
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' Retorno total express: ' . $response['response']);

            $response_a = json_decode($response['response']);

            // Atualizar ou criar encomenda
            $encomenda = Encomenda::select('id')->where('order', $order_id)->first();

            $encomenda_up = [
                'order' => $order_id,
                'pedido' => $response_a->retorno->encomendas[0]->pedido,
                'cliente_codigo' => $response_a->retorno->encomendas[0]->clienteCodigo,
                'tipo_servico' => $response_a->retorno->encomendas[0]->tipoServico,
                'data' => $response_a->retorno->encomendas[0]->data,
                'hora' => $response_a->retorno->encomendas[0]->hora,
                'volumes' => json_encode($response_a->retorno->encomendas[0]->volumes),
            ];

            if ($encomenda) {
                Encomenda::where('order', $order_id)->update($encomenda_up);
            } else {
                Encomenda::create($encomenda_up);
            }

            // Mudar status pedido
            OrderEvent::create([
                'order' => $order_id,
                'status' => 'request_total_express'
            ]);
            Order::select('id')->first()->where('id', $order_id)->update(['status' => 'request_total_express']);

            SendTotalFiles::dispatch($order_id);
        }
        
        return Command::SUCCESS;
    }

    private function curlExecute($fields)
    {
        if (env('TOTAL_EXPRESS_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init(env('TOTAL_EXPRESS_REGISTRA_COLETA'));

        if (env('TOTAL_EXPRESS_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        Log::info("Payload: " . $fields);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_USERPWD, env('TOTAL_EXPRESS_USER') . ":" . env('TOTAL_EXPRESS_PASSWORD')); /* If required */

        $response = curl_exec($curl);

        if (env('TOTAL_EXPRESS_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();

            Log::info(static::class . ' linha ' . __LINE__ . ' Debug: ' . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo: ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        return ['response' => $response, 'error' => $err];
    }
}
