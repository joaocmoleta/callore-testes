<?php

namespace App\Console\Commands;

use App\Models\OrderEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;

class OrderVerify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:verify {--remove=} {--history=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar os eventos em uma ordem e excluílos: Ex: pa order:verify --history={order_id} pa order:verify --remove={event_id}';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        if ($this->option('remove')) {
            $status = OrderEvent::select(
                'id',
                'order'
            )->where('id', $this->option('remove'))
                ->first();

                $this->line($status);

            if ($status) {
                $status->delete();
            } else {
                $this->line('Status não encontrado.');
            }

            $this->statusHistoryTable($status->order);
        }

        if ($this->option('history')) {
            $this->statusHistoryTable($this->option('history'));
        }

        return Command::SUCCESS;
    }

    private function statusHistoryTable($order)
    {
        $eventos = $this->statusHistory($order);

        $headers = ['id', 'Data', 'Evento'];

        $data = [];

        foreach ($eventos as $evento) {
            $data[] = [
                'id' => $evento->id,
                'time' => Carbon::parse($evento->created_at)->format('d/m/y H:i:s'),
                'event' => $evento->status,
            ];
        }
        $this->table($headers, $data);
    }

    private function statusHistory($order)
    {
        return OrderEvent::select(
            'id',
            'created_at',
            'status'
        )->where('order', $order)
            ->get();
    }
}
