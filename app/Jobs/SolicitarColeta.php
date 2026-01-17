<?php

namespace App\Jobs;

use App\Models\Encomenda;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SolicitarColeta implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->envValidate(); // Valida o .env

        $fields = $this->prepareFields();

        Log::info(static::class . ' linha ' . __LINE__ . ' Rodando SolicitarColeta');
        Log::info($fields);
        
        if ($fields) {
            $response = $this->curlTotalExpress($fields['fields']); // Roda o curl na api da total express
        } else {
            Log::error(static::class . ' linha ' . __LINE__ . ' Campos necessários incompletos.');
            exit();
        }

        if ($response['error']) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error: ' . $response['error']);
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' Retorno total express: ' . $response['response']);

            $response_a = json_decode($response['response']);

            // Atualizar ou criar encomenda
            $encomenda = Encomenda::select('id')->where('order', $fields['order_id'])->first();

            $encomenda_up = [
                'order' => $fields['order_id'],
                'pedido' => $response_a->retorno->encomendas[0]->pedido,
                'cliente_codigo' => $response_a->retorno->encomendas[0]->clienteCodigo,
                'tipo_servico' => $response_a->retorno->encomendas[0]->tipoServico,
                'data' => $response_a->retorno->encomendas[0]->data,
                'hora' => $response_a->retorno->encomendas[0]->hora,
                'volumes' => json_encode($response_a->retorno->encomendas[0]->volumes),
            ];

            if ($encomenda) {
                Encomenda::where('order', $fields['order_id'])->update($encomenda_up);
            } else {
                Encomenda::create($encomenda_up);
            }

            // Mudar status pedido
            OrderEvent::create([
                'order' => $fields['order_id'],
                'status' => 'request_total_express'
            ]);

            Order::select('id')->first()->where('id', $fields['order_id'])->update(['status' => 'request_total_express']);

            SendTotalFiles::dispatch($fields['order_id']);
        }
    }

    private function requestData()
    {
        return Order::select(
            'orders.id',
            'orders.products',
            'invoices.natureza',
            'invoices.numero',
            'invoices.serie',
            'invoices.data_emissao',
            'invoices.total',
            'invoices.produto',
            'invoices.chave',
            'invoices.volumes',
            'users.name',
            'users.doc',
            'completes.street',
            'completes.number',
            'completes.complement',
            'completes.locality',
            'completes.city',
            'completes.region_code',
            'completes.postal_code',
        )
            ->leftJoin('completes', 'completes.user', '=', 'orders.user')
            ->leftJoin('invoices', 'invoices.order', '=', 'orders.id')
            ->leftJoin('users', 'orders.user', '=', 'users.id')
            ->where('orders.id', $this->order_id)
            ->first();
    }

    private function calculateTotalWeight($produtos) {
        $total_weight = 0;
        foreach($produtos as $product) {
            $prod = Product::select(
                'technical_specifications.pack_sizes'
            )
                ->leftJoin('technical_specifications', 'technical_specifications.product', 'products.id')
                ->where('products.id', $product->id)
                ->first();

            $weight = json_decode($prod->pack_sizes)->we;

            $total_weight += $weight * $product->qtd;
        }

        return $total_weight;
    }

    private function prepareFields()
    {
        $order = $this->requestData();

        if (
            $order->id == null
            || $order->products == null
            || $order->natureza == null
            || $order->name == null
            || $order->doc == null
            || $order->street == null
            || $order->number == null
            // || $order->complement == null
            || $order->locality == null
            || $order->city == null
            || $order->region_code == null
            || $order->postal_code == null
            || $order->numero == null
            || $order->serie == null
            || $order->data_emissao == null
            || $order->total == null
            || $order->produto == null
            || $order->chave == null
            || $order->volumes == null
        ) {
            return false;
        }

        $total_weight = $this->calculateTotalWeight(json_decode($order->products));

        return [
            'order_id' => $order->id,
            'fields' => json_encode([
                "remetenteId" => env('TOTAL_EXPRESS_ID'),
                "cnpj" => env('CNPJ'),
                "encomendas" => [
                    [
                        "servicoTipo" => 1,
                        "entregaTipo" => "0",
                        "peso" => $total_weight,
                        "volumes" => $order->volumes,
                        "condFrete" => "CIF",
                        "pedido" => "pedido-" . $order->id,
                        "natureza" => substr($order->natureza, 0, 25),
                        "icmsIsencao" => 1,
                        "destinatario" => [
                            "nome" => $order->name,
                            "cpfCnpj" => preg_replace("/[^0-9]/", "", $order->doc),
                            "endereco" => [
                                "logradouro" => $order->street,
                                "numero" => $order->number,
                                "complemento" => $order->complement,
                                "pontoReferencia" => '',
                                "bairro" => $order->locality,
                                "cidade" => $order->city,
                                "estado" => $order->region_code,
                                "cep" => $order->postal_code
                            ],
                        ],
                        "docFiscal" => [
                            "nfe" => [
                                [
                                    "nfeNumero" => $order->numero,
                                    "nfeSerie" => $order->serie,
                                    "nfeData" => $order->data_emissao,
                                    "nfeValTotal" => $order->total,
                                    "nfeValProd" => $order->produto,
                                    "nfeChave" => $order->chave
                                ]
                            ]
                        ]
                    ]
                ]
            ])
        ];
    }

    private function envValidate()
    {
        if (env('TOTAL_EXPRESS_ID') == '' || env('TOTAL_EXPRESS_USER') == '' || env('TOTAL_EXPRESS_PASSWORD') == '') {
            Log::error(static::class . ' linha ' . __LINE__ . ' Erro nas envioments.');
            Log::info(static::class . ' linha ' . __LINE__ . ' TOTAL_EXPRESS_ID: ' . env('TOTAL_EXPRESS_ID'));
            Log::info(static::class . ' linha ' . __LINE__ . ' TOTAL_EXPRESS_USER: ' . env('TOTAL_EXPRESS_USER'));
            Log::info(static::class . ' linha ' . __LINE__ . ' TOTAL_EXPRESS_PASSWORD: ' . env('TOTAL_EXPRESS_PASSWORD'));
            exit();
        }
    }

    private function curlTotalExpress($fields)
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
