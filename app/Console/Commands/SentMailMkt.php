<?php

namespace App\Console\Commands;

use App\Jobs\EnvioNewsletterJob;
use Illuminate\Console\Command;

class SentMailMkt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sent:mail-mkt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar email mkt';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        EnvioNewsletterJob::dispatch();
        return Command::SUCCESS;
    }
}
