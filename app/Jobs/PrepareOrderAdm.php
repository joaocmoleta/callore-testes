<?php

namespace App\Jobs;

use App\Mail\AdmPrepareMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PrepareOrderAdm implements ShouldQueue
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
        if (env('DEBUG_MOL')) {
            if (env('MAIL_SUPPORT')) {
                Log::info(static::class . ' linha ' . __LINE__ . ' Enviando e-mail de preparar pedido para ' . env('MAIL_SUPPORT'));
                Mail::to(env('MAIL_SUPPORT'))->send(new AdmPrepareMail($this->order));
            }
            return;
        }

        if (env('MAIL_ADM')) {
            Log::info(static::class . ' linha ' . __LINE__ . ' Enviando e-mail de preparar pedido para ' . env('MAIL_ADM'));
            Mail::to(env('MAIL_ADM'))->send(new AdmPrepareMail($this->order));
        }

        if (env('MAIL_MANAGER')) {
            Log::info(static::class . ' linha ' . __LINE__ . ' Enviando e-mail de preparar pedido para ' . env('MAIL_MANAGER'));
            Mail::to(env('MAIL_MANAGER'))->send(new AdmPrepareMail($this->order));
        }

        if (env('MAIL_MKT')) {
            Log::info(static::class . ' linha ' . __LINE__ . ' Enviando e-mail de preparar pedido para ' . env('MAIL_MKT'));
            Mail::to(env('MAIL_MKT'))->send(new AdmPrepareMail($this->order));
        }
    }
}
