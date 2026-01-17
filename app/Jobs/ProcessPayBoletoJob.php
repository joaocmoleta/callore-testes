<?php

namespace App\Jobs;

use App\Helpers\PagSeguroHelper;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessPayBoletoJob implements ShouldQueue
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
            Log::info(static::class . ' linha ' . __LINE__ . ' Erro ao comunicar com gateway de pagamento (Boleto)');
            exit();
        }
        
        $res = json_decode($this->response);

        // Gravar ordem status
        $order = Order::select('id', 'status', 'payment')->where('id', $res->reference_id)->first();
        
        $new_status = 'declined';
        if($res->status == 'WAITING') {
            $new_status = 'waiting_pay_boleto';
        }
        $order->status = $new_status;
        $order->save();

        // Gravar payment pay_id
        $payment_data = [
            'pay_id' => $res->id,
            'amount' => PagSeguroHelper::instance()->amount_to_double($res->amount->value),
            'cd_holder_name' => $res->payment_method->boleto->holder->name,
            'boleto_id' => $res->payment_method->boleto->id,
            'barcode' => $res->payment_method->boleto->barcode,
            'due_date' => $res->payment_method->boleto->due_date,
            'links' => json_encode($res->links),
        ];

        $payment = Payment::where('id', $order->payment)->update($payment_data);
        if(!$payment) {
            Payment::create($payment_data);
        }
        
        OrderEvent::create([
            'order' => $res->reference_id,
            'status' => $new_status,
        ]);

        GerBoletoJob::dispatch($order);
    }
}
