<?php

namespace App\Jobs;

use App\Helpers\PagSeguroHelper;
use App\Models\Complete;
use App\Models\Order;
use App\Models\OrderEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreditPagSegJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $other;
    private int $amount;
    private int $installments;
    private $complete;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($other)
    {
        $this->other = $other;
        $this->installments = $other['installments'];
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
            Log::error(static::class . ' linha ' . __LINE__ . ' Erro nas envioments utilizadas no pagamento (CreditPagSegJob).');
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_TOKEN (CreditPagSegJob): ' . env('PAGSEGURO_TOKEN'));
            Log::info(static::class . ' linha ' . __LINE__ . ' NOTIFICATION_URL (CreditPagSegJob): ' . env('NOTIFICATION_URL'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_URL (CreditPagSegJob): ' . env('PAGSEGURO_URL'));
            exit();
        }

        $fields = json_encode([
            'reference_id' => $this->other['reference_id'],
            'customer' => [
                'name' => $this->other['card_holder_name'],
                'email' => $this->other['user']->email,
                'tax_id' => $this->other['complete']->tax_id,
                'phones' => [
                    [
                        'country' => $this->other['ddi'],
                        'area' => $this->other['ddd'],
                        'number' => preg_replace('/\D/', '', $this->other['phone']),
                        'type' => 'MOBILE'
                    ]
                ]
            ],
            'items' => [
                [
                    'name' => 'outros',
                    'quantity' => 1,
                    'unit_amount' => $this->amount
                ]
            ],
            'shipping' => [
                'address' => [
                    'street' => $this->other['complete']->street,
                    'number' => $this->other['complete']->number,
                    'complement' => $this->other['complete']->complement,
                    'locality' => $this->other['complete']->locality,
                    'city' => $this->other['complete']->city,
                    'region_code' => $this->other['complete']->region_code,
                    'country' => $this->other['complete']->country,
                    'postal_code' => $this->other['complete']->postal_code
                ]
            ],
            'notification_urls' => [
                env('NOTIFICATION_URL')
            ],
            'charges' => [
                [
                    'reference_id' => $this->other['reference_id'],
                    'description' => $this->other['description'],
                    'amount' => [
                        'value' => $this->amount,
                        'currency' => 'BRL'
                    ],
                    'payment_method' => [
                        'type' => 'CREDIT_CARD',
                        'installments' => $this->installments,
                        'capture' => true,
                        'card' => [
                            'encrypted' => $this->other['encrypted'],
                            'security_code' => $this->other['card_security_code'],
                            'holder' => [
                                'name' => $this->other['card_holder_name']
                            ],
                            'store' => false
                        ]
                    ]
                ]
            ]
        ]);

        if(env('PAGSEGURO_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init(env('PAGSEGURO_URL') . 'orders');

        if(env('PAGSEGURO_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        Log::info("fields (CreditPagSegJob): " . $fields);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: ' . env('PAGSEGURO_TOKEN'),
            'Content-Type: application/json',
        ]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);

        $response = curl_exec($curl);

        if(env('PAGSEGURO_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();
    
            Log::info(static::class . ' linha ' . __LINE__ . ' Debug (CreditPagSegJob): ' . $debug);
        }
        
        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo (CreditPagSegJob): ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error (CreditPagSegJob): ' . curl_error($curl));
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' Retorno pagseguro (CreditPagSegJob): ' . $response);

            $res = json_decode($response);

            if (isset($res->reference_id)) {
                ProcessPayCreditJob::dispatch($response);
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
