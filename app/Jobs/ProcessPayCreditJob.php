<?php

namespace App\Jobs;

use App\Events\PaidOrderEvent;
use App\Helpers\PagSeguroHelper;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPayCreditJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $response;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(!$this->response) {
            Log::info(static::class . ' linha ' . __LINE__ . ' ProcessPayCreditJob: Erro ao comunicar com gateway de pagamento (Crédito)');
            exit();
        }

        $res = json_decode($this->response);

        $order = Order::select('id', 'status', 'payment')->where('id', $res->reference_id)->first();

        $new_status = strtolower($res->charges[0]->status);
        $order->status = $new_status;
        $order->save();

        // Gravar payment pay_id
        $payment_data = [
            'pay_id' => $res->id,
            'amount' => PagSeguroHelper::instance()->amount_to_double($res->charges[0]->amount->value),
            'links' => json_encode($res->charges[0]->links),
            'installments' => $res->charges[0]->payment_method->installments,
            'cd_brand' => $res->charges[0]->payment_method->card->brand,
            'cd_first_digits' => $res->charges[0]->payment_method->card->first_digits,
            'cd_last_digits' => $res->charges[0]->payment_method->card->last_digits,
            'cd_exp_month' => $res->charges[0]->payment_method->card->exp_month,
            'cd_exp_year' => $res->charges[0]->payment_method->card->exp_year,
            'cd_holder_name' => $res->charges[0]->payment_method->card->holder->name,
        ];

        $payment = Payment::where('id', $order->payment)->update($payment_data);
        if (!$payment) {
            Payment::create($payment_data);
        }

        OrderEvent::create([
            'order' => $res->reference_id,
            'status' => $new_status,
        ]);

        if($res->charges[0]->status == 'PAID') {
            // PaidOrderJob::dispatch($order);
            PrepareOrderJob::dispatch($order);
            // Avisar adm
            PrepareOrderAdm::dispatch($order);
        }
    }
}
