<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function (object $notificable, string $url) {
            Log::info(static::class . ' linha ' . __LINE__ . ' Enviando e-mail de verificação e aceite dos termos para o comprador.');

            $mail = (new MailMessage)
                ->subject('Verificação de e-mail e aceite dos termos')
                ->action('Concordar e confirmar', $url);

            $bccs = [];

            if (env('DEBUG_MOL')) {
                if (env('MAIL_SUPPORT')) {
                    Log::info(static::class . ' linha ' . __LINE__ . ' Enviando cópia de e-mail de verificação e aceite dos termos para ' . env('MAIL_SUPPORT'));
                    $bccs[] = env('MAIL_SUPPORT');
                }
            } else {
                if (env('MAIL_ADM')) {
                    Log::info(static::class . ' linha ' . __LINE__ . ' Enviando cópia de e-mail de verificação e aceite dos termos para ' . env('MAIL_ADM'));
                    $bccs[] = env('MAIL_ADM');
                }
                if (env('MAIL_MKT')) {
                    Log::info(static::class . ' linha ' . __LINE__ . ' Enviando cópia de e-mail de verificação e aceite dos termos para ' . env('MAIL_MKT'));
                    $bccs[] = env('MAIL_MKT');
                }
            }

            $mail->bcc($bccs);

            return $mail;
        });
    }
}
