<?php

namespace App\Listeners;

use App\Events\Verified;
use App\Mail\DoubleCheck as MailDoubleCheck;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DoubleCheck
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Verified  $event
     * @return void
     */
    public function handle($event)
    {
        $bccs = [];

        if (env('DEBUG_MOL')) {
            if (env('MAIL_SUPPORT')) {
                Log::info(static::class . ' linha ' . __LINE__ . ' Enviando cópia de e-mail de dupla verificação para ' . env('MAIL_SUPPORT'));
                // Mail::to(env('MAIL_SUPPORT'))->send(new MailDoubleCheck());
                $bccs[] = env('MAIL_SUPPORT');
            }
        } else {
            if (env('MAIL_ADM')) {
                Log::info(static::class . ' linha ' . __LINE__ . ' Enviando cópia de e-mail de dupla verificação para ' . env('MAIL_ADM'));
                // Mail::to(env('MAIL_ADM'))->send(new MailDoubleCheck());
                $bccs[] = env('MAIL_ADM');
            }
    
            if (env('MAIL_MKT')) {
                Log::info(static::class . ' linha ' . __LINE__ . ' Enviando cópia de e-mail de dupla verificação para ' . env('MAIL_MKT'));
                // Mail::to(env('MAIL_MKT'))->send(new MailDoubleCheck());
                $bccs[] = env('MAIL_MKT');
            }
        }

        Log::info(static::class . ' linha ' . __LINE__ . ' Enviando e-mail(s) de dupla verificação.');

        Mail::to($event->user->email)
            ->bcc($bccs)
            ->send(new MailDoubleCheck());
    }
}
