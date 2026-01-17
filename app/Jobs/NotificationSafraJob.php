<?php

namespace App\Jobs;

use App\Helpers\CouponHelper;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\Payment;
use App\Models\SafraPedidos;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationSafraJob implements ShouldQueue
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
        // body que vem de uma notificação
        Log::info(__CLASS__ . ' linha ' . __LINE__ . ' ' . $this->response);

        $response = json_decode($this->response);

        $order_get_id = SafraPedidos::select('order')
            ->where('charge_id', $response->ChargeId)
            ->first();

        $order = Order::select('id', 'amount', 'status', 'coupon', 'products', 'payment')
            ->where('id', $order_get_id->order)
            ->first();

        // Verificar se tem cupom associado ao pedido e calcular total antes de comparar
        if ($order->coupon) {
            $payment = Payment::select('type')
                ->where('id', $order->payment)
                ->first();

            $amount_complete = CouponHelper::instance()->get_amount($order->coupon, json_decode($order->products), false, $payment->type);
        } else {
            $amount_complete = [
                "amount" => $order->amount,
                "discount" => 0,
                "coupon" => null
            ];
        }

        if ($response->Transactions[0]->IsApproved && $order->status != 'Authorized') {
            if ($response->Transactions[0]->Amount / 100 == $amount_complete['amount']) {
                $order->update([
                    'status' => 'Authorized'
                ]);

                OrderEvent::create([
                    'order' => $order->id,
                    'status' => 'Authorized',
                ]);

                // Disparar e-email
                $order = Order::select(
                    'user',
                    'id',
                    'products',
                    'coupon',
                )
                    ->where('id', $order->id)
                    ->first();

                PrepareOrderAdm::dispatch($order);
            } else {
                $order->update([
                    'status' => 'divergencia_valores'
                ]);

                OrderEvent::create([
                    'order' => $order->id,
                    'status' => 'divergencia_valores',
                ]);
            }
        }
    }
}
