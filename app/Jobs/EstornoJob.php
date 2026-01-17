<?php

namespace App\Jobs;

use App\Helpers\PagSeguroHelper;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EstornoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payment = Payment::select('*')->where('id', $this->order->payment)->first();

        $amount = PagSeguroHelper::instance()->format_amount($payment->amount);

        if (env('PAGSEGURO_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init();

        if (env('PAGSEGURO_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('PAGSEGURO_URL') . 'charges/' . $payment->pay_id . '/cancel',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
        "amount": {
            "value": ' . $amount . '
        }
        }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . env('PAGSEGURO_TOKEN') . '',
                'Content-Type: application/json',
                'x-api-version: 4.0'
            ),
        ));

        $response = curl_exec($curl);

        if (env('PAGSEGURO_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();

            Log::info(static::class . ' linha ' . __LINE__ . ' Debug (EstornoJob): ' . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo (EstornoJob): ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));
        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error (EstornoJob): ' . curl_error($curl));
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' Retorno pagseguro (EstornoJob): ' . $response);
            ProcessEstornoJob::dispatch($response);
        }
    }
}
