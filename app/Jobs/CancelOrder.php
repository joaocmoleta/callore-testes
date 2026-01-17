<?php

namespace App\Jobs;

use App\Mail\CancelOrder as MailCancelOrder;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CancelOrder implements ShouldQueue
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
        $order = Order::select('user')
        ->where('id', $this->order_id)
        ->first();
        
        if(env('DEBUG_MOL')) {
            if(env('MAIL_SUPPORT')) {
                Mail::to(env('MAIL_SUPPORT'))->send(new MailCancelOrder($this->order_id));
                Log::info(static::class . ' Linha ' . __LINE__ . " Enviado e-mail de cancelamento do pedido $this->order_id para " . env('MAIL_SUPPORT'));
            }
            return;
        }

        if(env('MAIL_ADM')) {
            Mail::to(env('MAIL_ADM'))->send(new MailCancelOrder($this->order_id));
            Log::info(static::class . ' Linha ' . __LINE__ . " Enviado e-mail de cancelamento do pedido $this->order_id para " . env('MAIL_ADM'));
        }
        
        if(env('MAIL_MANAGER')) {
            Mail::to(env('MAIL_MANAGER'))->send(new MailCancelOrder($this->order_id));
            Log::info(static::class . ' Linha ' . __LINE__ . " Enviado e-mail de cancelamento do pedido $this->order_id para " . env('MAIL_MANAGER'));
        }
        
        if(env('MAIL_MKT')) {
            Mail::to(env('MAIL_MKT'))->send(new MailCancelOrder($this->order_id));
            Log::info(static::class . ' Linha ' . __LINE__ . " Enviado e-mail de cancelamento do pedido $this->order_id para " . env('MAIL_MKT'));
        }
    }
}
