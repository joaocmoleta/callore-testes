<?php

namespace App\Jobs;

use App\Mail\PayFail;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\PagarmePedidos;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessNotificationPM implements ShouldQueue
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
        $response = json_decode($this->response);

        if (!isset($response->type)) {
            Log::error(static::class . ' linha ' . __LINE__ . ": contém erros no processamento da resposta: " . $this->response);
            return;
        }

        // Verificar valor
        $pagarmepedidos = PagarmePedidos::select('order', 'paid_amount')->where('id_order', $response->data->id)->first();

        if ($pagarmepedidos) {
            if ($pagarmepedidos->paid_amount != $response->data->amount) {
                Log::error(static::class . ' linha ' . __LINE__ . " Pedido ($pagarmepedidos->order): O valor pago é diferente do total.");
                return;
            }

            $order = Order::select('payment', 'status')->where('id', $pagarmepedidos->order)->first();
            if($order->status == $response->data->charges[0]->status) {
                return;
            }

            // Atualizo banco de dados
            Order::where('id', $pagarmepedidos->order)->update(['status' => $response->data->charges[0]->status]);
            Payment::where('id', $order->payment)->update(['pay_id' => $response->data->charges[0]->id]);
            OrderEvent::created(['order' => $pagarmepedidos->order, 'status' => $response->data->charges[0]->status]);

            if ($response->type == 'order.paid') {
                PagarmePedidos::where('id_order', $response->data->id)->update(['paid_amount' => $response->data->charges[0]->paid_amount]);
                // Disparo preparar pedido
                // Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($pagarmepedidos->order): Disparar preparar pedido.");
                PrepareOrderAdm::dispatch(Order::select('id', 'user', 'coupon', 'amount', 'products')->where('id', $pagarmepedidos->order)->first());
            }

            if ($response->type == 'order.failed') {
                $order_and_user = Order::select(
                    'orders.id',
                    'orders.user',
                    'orders.coupon',
                    'orders.amount',
                    'orders.products',
                    'orders.status',
                    'users.email'
                )
                    ->leftJoin('users', 'users.id', '=', 'orders.user')
                    ->where('orders.id', $pagarmepedidos->order)
                    ->first();

                Log::info(static::class . ' Linha ' . __LINE__ . " Pedido $pagarmepedidos->order: Enviando e-mail de aviso de falha de pagamento.");
                Mail::to($order_and_user->email)->send(new PayFail($order_and_user));
            }

            Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($pagarmepedidos->order): Pagamento atualizado.");
        } else {
            Log::error(static::class . ' linha ' . __LINE__ . " Pedido não localizado para identificação: " . $response->data->id);
        }
    }
}
