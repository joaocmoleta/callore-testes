<?php

namespace App\Console\Commands;

use App\Helpers\CouponHelper;
use Illuminate\Console\Command;

class Teste2025 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teste:2025';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('teste');

        $products = json_decode('[{"id":"17","name":"Aquecedor de Toalhas Callore Fam\u00edlia | Branco | 127V","slug":"aquecedor-de-toalhas-callore-familia-branco-127v","thumbnail":"\/uploads\/aquecedor-de-toalhas-callore-familia-branco-790x1200.webp","qtd":1,"value_uni":2328.4,"subtotal":2328.4}]');

        $discount = CouponHelper::instance()->get_amount('CIBELE250220', $products, true);

        dd($discount);
        
        return Command::SUCCESS;
    }
}
