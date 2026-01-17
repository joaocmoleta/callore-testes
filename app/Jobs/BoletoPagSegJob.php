<?php

namespace App\Jobs;

use App\Helpers\PagSeguroHelper;
use App\Models\Order;
use App\Models\OrderEvent;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BoletoPagSegJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $other;
    private int $amount;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($other)
    {
        $this->other = $other;
        $this->amount = PagSeguroHelper::instance()->format_amount($other['amount']);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (env('PAGSEGURO_TOKEN') == '' || env('NOTIFICATION_URL') == '' || env('PAGSEGURO_URL') == '') {
            Log::error(static::class . ' linha ' . __LINE__ . ' Erro nas envioments utilizadas no pagamento (BoletoPagSegJob).');
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_TOKEN (BoletoPagSegJob): ' . env('PAGSEGURO_TOKEN'));
            Log::info(static::class . ' linha ' . __LINE__ . ' NOTIFICATION_URL (BoletoPagSegJob): ' . env('NOTIFICATION_URL'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_URL (BoletoPagSegJob): ' . env('PAGSEGURO_URL'));
            exit();
        }

        $fields = json_encode([
            'reference_id' => $this->other['reference_id'],
            'description' => $this->other['description'],
            "amount" => [
                'value' => $this->amount,
                "currency" => "BRL"
            ],
            "payment_method" => [
                "type" => "BOLETO",
                "boleto" => [
                    "due_date" => Carbon::now()->add(1, 'day')->format('Y-m-d'),
                    // "due_date" => "2024-12-31",
                    "instruction_lines" => [
                        "line_1" => $this->other['description'],
                        "line_2" => "Via PagSeguro"
                    ],
                    "holder" => [
                        "name" => $this->other['card_holder_name'],
                        "tax_id" => $this->other['tax_id'], // cpf cnpj
                        "email" => $this->other['email'],
                        "address" => [
                            "street" => $this->other['street'],
                            "number" => $this->other['number'],
                            "locality" => $this->other['locality'],
                            "city" => $this->other['city'],
                            "region" => $this->other['region'],
                            "region_code" => $this->other['region_code'],
                            "country" => $this->other['country'],
                            "postal_code" => $this->other['postal_code']
                        ]
                    ]
                ]
            ],
            "notification_urls" => [
                env('NOTIFICATION_URL')
            ]
        ]);

        Log::info(static::class . ' linha ' . __LINE__ . ' Iniciando comunicação com pagseguro (BoletoPagSegJob) ###################################################');

        if (env('PAGSEGURO_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init(env('PAGSEGURO_URL') . 'charges');

        if (env('PAGSEGURO_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        Log::info("fields (BoletoPagSegJob): " . $fields);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: ' . env('PAGSEGURO_TOKEN'),
            'Content-Type: application/json',
            'x-api-version: 4.0',
            'x-idempotency-key;'
        ]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);

        $response = curl_exec($curl);

        if (env('PAGSEGURO_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();

            Log::info(static::class . ' linha ' . __LINE__ . ' Debug (BoletoPagSegJob): ' . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo (BoletoPagSegJob): ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error (BoletoPagSegJob): ' . curl_error($curl));
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' Retorno pagseguro (BoletoPagSegJob): ' . $response);

            $res = json_decode($response);

            if (isset($res->reference_id)) {
                ProcessPayBoletoJob::dispatch($response);
            } else {
                Order::select('id')->where('id', $this->other['reference_id'])->first()->update([
                    'status' => 'declined'
                ]);

                OrderEvent::create([
                    'order' => $this->other['reference_id'],
                    'status' => 'declined'
                ]);
            }
        }
    }
}
