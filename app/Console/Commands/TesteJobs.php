<?php

namespace App\Console\Commands;

use App\Jobs\TesteJob;
use Illuminate\Console\Command;

class TesteJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teste:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando solicita um job que gera um log, testando o sistema de filas.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        TesteJob::dispatch();
        return Command::SUCCESS;
    }
}
