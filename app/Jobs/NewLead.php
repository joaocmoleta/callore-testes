<?php

namespace App\Jobs;

use App\Mail\NotifyNewLead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NewLead implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
                Mail::to(env('MAIL_SUPPORT'))->send(new NotifyNewLead($this->data));
                Log::info(static::class . ' linha ' . __LINE__ . ' Enviado e-mail para ' . env('MAIL_SUPPORT'));
            }
            return;
        }

        if(env('MAIL_MKT')) {
            Mail::to(env('MAIL_MKT'))->send(new NotifyNewLead($this->data));
            Log::info(static::class . ' linha ' . __LINE__ . ' Enviado e-mail para ' . env('MAIL_MKT'));
        }
    }
}
