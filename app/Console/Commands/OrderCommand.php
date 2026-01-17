<?php

namespace App\Console\Commands;

use App\Jobs\PrepareOrderAdm;
use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Console\Command;

class OrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:do {order} {--action=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comandos na ordem. order:do 1 --action=resendSaleNotification  --action=pedidos-em-aberto';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if($this->option('action') == 'pedidos-em-aberto') {
            $deliveries = Delivery::select(
                'id',
                'status',
                'method'
            )
            ->where('order', $this->argument('order'))
            ->get();

            $this->table(['id', 'Status', 'Método'], $deliveries);

            return Command::SUCCESS;
        }

        // Reenviar e-mail de notificação nova venda
        if ($this->option('action') == 'resendSaleNotification') {
            $order = Order::select(
                'user',
                'id',
                'products',
                'coupon',
            )
            ->where('id', $this->argument('order'))
            ->first();

            $schedule = PrepareOrderAdm::dispatch($order);
            if($schedule) {
                $this->line('Agendado envio de notificação.');
            }
        }

        return Command::SUCCESS;
    }
}
