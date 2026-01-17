<?php

namespace App\Jobs;

use App\Helpers\CouponHelper;
use App\Helpers\PagSeguroHelper;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreditPagarMeSent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $other;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($other)
    {
        $this->other = $other;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (env('PAGARME_URL') == '' || env('PAGARME_SK') == '') {
            Log::error(static::class . ' linha ' . __LINE__ . " Pedido (" . $this->other['order'] . "): Erro nas envioments utilizadas no pagamento.");
            Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $this->other['order'] . ") - PAGARME_DEBUG: " . env('PAGARME_DEBUG'));
            Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $this->other['order'] . ") - PAGARME_URL: " . env('PAGARME_URL'));
            Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $this->other['order'] . ") - PAGARME_SK: " . env('PAGARME_SK'));
            Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $this->other['order'] . ") - PAGARME_SK_PWD: " . env('PAGARME_SK_PWD'));
            exit();
        }

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $this->other['order'] . "): Iniciado preparo dos dados para pagamento cartão de crédito PagarMe.");
        $order = Order::select(
            'orders.products',
            'orders.coupon',
            'orders.amount',
            'users.id as user_id',
            'users.name',
            'users.doc',
            'users.email',
            'users.birth',
            'users.phone',
            'completes.country',
            'completes.region_code',
            'completes.city',
            'completes.locality',
            'completes.complement',
            'completes.number',
            'completes.street',
            'completes.postal_code',
        )
            ->where('orders.id', $this->other['order'])
            ->leftJoin('users', 'users.id', '=', 'orders.user')
            ->leftJoin('completes', 'completes.user', '=', 'users.id')
            ->first();

        $doc = preg_replace('/[^\d]/', '', $order->doc);
        if (strlen($doc) > 11) {
            $doc_type['type'] = 'CNPJ';
            $doc_type['type_ext'] = 'company';
        } else {
            $doc_type['type'] = 'CPF';
            $doc_type['type_ext'] = 'individual';
        }

        $phone_cl = preg_replace('/[^\d]/', '', $order->phone);

        $phone = [
            'ddi' => substr($phone_cl, 0, 2),
            'ddd' => substr($phone_cl, 2, 2),
            'phone' => substr($phone_cl, 4),
        ];

        $amount = $order->amount;

        $fields = [
            "customer" => [
                "address" => [
                    "country" => "BR",
                    "state" => "BR-$order->region_code",
                    "city" => $order->locality,
                    "zip_code" => $order->postal_code,
                    "line_1" => "$order->street, $order->number, $order->locality",
                    "line_2" => $order->complement
                ],
                "phones" => [
                    "mobile_phone" => [
                        "country_code" => $phone['ddi'],
                        "area_code" => $phone['ddd'],
                        "number" => $phone['phone']
                    ]
                ],
                "name" => $order->name,
                "type" => $doc_type['type_ext'], // individual (pessoa física) ou company (pessoa jurídica).
                "email" => $order->email,
                "code" => $order->user_id,
                "document" => preg_replace('/[^\d]/', '', $order->doc),
                "document_type" => $doc_type['type'],
                "gender" => "",
                // "birthdate" => Carbon::create($order->birth)->format('m/d/y')   // não -> "01/01/1990" correto mm/dd/aa
            ],
            "items" => [],
            "payments" => [
                [
                    "credit_card" => [
                        "card" => [
                            "billing_address" => [
                                "line_1" => "$order->street, $order->number, $order->locality",
                                "country" => "BR",
                                "state" => "BR-$order->region_code",
                                "city" => $order->locality,
                                "zip_code" => $order->postal_code,
                                "line_2" => $order->complement
                            ]
                        ],
                        // https://docs.pagar.me/page/mitcit-transa%C3%A7%C3%B5es-card-on-file-mastercard para initiated_type e recurrence_model
                        // "initiated_type" => "string", // Novo campo
                        "recurrence_model" => "installment", // Novo campo
                        "card_token" => $this->other['payment']['pagarmetoken-0'],
                        "operation_type" => "auth_and_capture",
                        "installments" => $this->other['payment']['installments'],
                        "statement_descriptor" => "CALLORE"
                    ],
                    "payment_method" => "credit_card",
                    "amount" => PagSeguroHelper::instance()->format_amount($amount),
                ]
            ],
            "closed" => true,
            "antifraud_enabled" => true
        ];

        $items = json_decode($order->products);

        foreach ($items as $item) {
            $amount = $item->value_uni;
            $amount = PagSeguroHelper::instance()->format_amount($amount);

            $fields['items'][] = [
                "amount" => $amount, // valor unitário
                "description" => $item->name,
                "quantity" => $item->qtd,
                "code" => $item->id
            ];
        }

        $fields = json_encode($fields);

        // json_encode($fields);
        // Log::info($fields);
        // exit();

        if (env('PAGSEGURO_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init(env('PAGARME_URL'));

        if (env('PAGSEGURO_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        Log::info(static::class . ' linha ' . __LINE__ . " Payload do pedido (" . $this->other['order'] . "): " . $fields);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode(env('PAGARME_SK') . ':' . env('PAGARME_SK_PWD')),
            'Content-Type: application/json',
        ]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $this->other['order'] . "): Iniciando transmissão dos dados...");

        $response = curl_exec($curl);

        if (env('PAGSEGURO_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();

            Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $this->other['order'] . ") - Debug: " . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $this->other['order'] . ") - curl_getinfo: " . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . " Pedido (" . $this->other['order'] . ") - curl_error:" . curl_error($curl));
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $this->other['order'] . ") - Retorno pagarme: " . $response);
            CreditPagarMeProcess::dispatch([$response, $this->other['order']]);
        }

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $this->other['order'] . "): finalizado envio de pagamento cartão de crédito PagarMe com ou sem erros.");
    }
}
