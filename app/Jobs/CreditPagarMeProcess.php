<?php

namespace App\Jobs;

use App\Mail\PayFail;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\PagarmePedidos;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreditPagarMeProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $response;
    private $order_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($other)
    {
        $this->response = $other[0];
        $this->order_id = $other[1];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($this->order_id): iniciado tratamento de resposta pagamento Cartão de crédito PagarMe.");

        $response = json_decode($this->response);

        if (!isset($response->status)) {
            Log::error(static::class . ' linha ' . __LINE__ . " Pedido ($this->order_id) - contém erros no processamento da resposta: " . $this->response);
            return;
        }

        Order::where('id', $this->order_id)
            ->update([
                'status' => $response->status,
            ]);

        $order = Order::select('payment')->where('id', $this->order_id)->first();

        Payment::where('id', $order->payment)->update([
            'pay_id' => $response->charges[0]->id,
            'amount' => $response->charges[0]->amount,
            'cd_first_digits' => $response->charges[0]->last_transaction->card->first_six_digits,
            'cd_last_digits' => $response->charges[0]->last_transaction->card->last_four_digits,
            'cd_brand' => $response->charges[0]->last_transaction->card->brand,
            'cd_exp_month' => $response->charges[0]->last_transaction->card->exp_month,
            'cd_exp_year' => $response->charges[0]->last_transaction->card->exp_year,
        ]);

        $pagarme_pedidos = [
            'order' => $this->order_id,
            'id_order' => $response->id,
            'code' => $response->code,
            'closed' => $response->closed,
            'pg_created_at' => $response->created_at,
            'pg_updated_at' => $response->updated_at,
            'pg_closed_at' => $response->closed_at,
            'charge_id' => $response->charges[0]->id,
            'charge_code' => $response->charges[0]->code,
            'transaction_id' => $response->charges[0]->last_transaction->id,
        ];

        if ($response->status == 'paid') {
            $pagarme_pedidos['gateway_id'] = $response->charges[0]->last_transaction->gateway_id;
            $pagarme_pedidos['paid_amount'] = $response->charges[0]->paid_amount;

            // Disparar o prepar pedido
            // Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($this->order_id): Disparar preparar pedido.");
            PrepareOrderAdm::dispatch(Order::select('id', 'user', 'coupon', 'amount', 'products')->where('id', $this->order_id)->first());
        }

        if($response->status == 'failed') {
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
            ->where('orders.id', $this->order_id)
            ->first();

            Log::info(static::class . ' Linha ' . __LINE__ . " Pedido $this->order_id: Enviando e-mail de aviso de falha de pagamento.");
            Mail::to($order_and_user->email)->send(new PayFail($order_and_user));
        }

        PagarmePedidos::create($pagarme_pedidos);

        OrderEvent::create([
            'order' => $this->order_id,
            'status' => $response->status,
        ]);

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($this->order_id): Finalizado.");
    }
}
