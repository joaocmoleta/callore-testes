<?php

namespace App\Jobs;

use App\Mail\NewOrderMail;
use App\Models\OrderEvent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NewOrderJob implements ShouldQueue
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
        $user = User::select('email')->where('id', $this->order->user)->first();

        OrderEvent::create(['order' => $this->order->id, 'status' => 'new']);

        Mail::to($user->email)->send(new NewOrderMail($this->order));
        Log::info(static::class . ' linha ' . __LINE__ . " Enviado e-mail para o comprador $user->email.");

        if (env('DEBUG_MOL')) {
            if (env('MAIL_SUPPORT')) {
                Mail::to(env('MAIL_SUPPORT'))->send(new NewOrderMail($this->order));
                Log::info(static::class . ' linha ' . __LINE__ . " Enviado cópia para o suporte " . env('MAIL_SUPPORT'));
            }
        } else {
            if (env('MAIL_MKT')) {
                Mail::to(env('MAIL_MKT'))->send(new NewOrderMail($this->order));
                Log::info(static::class . ' linha ' . __LINE__ . " Enviado cópia para o marketing " . env('MAIL_MKT'));
            }
        }

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido " . $this->order->id . " adicionado ao usuário " . $this->order->user . ".");
    }
}
