<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessEstornoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $response;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info(static::class . ' linha ' . __LINE__ . ' Estorno response: ' . $this->response);
        
        $res = json_decode($this->response);

        $order = Order::select('id', 'status', 'payment')->where('id', $res->reference_id)->first();

        $new_status = 'Erro ao cancelar';
        if ($res->status == 'CANCELED') {
            $new_status = 'Cancelado';
        }
        $order->status = $new_status;
        $order->save();

        OrderEvent::create([
            'order' => $res->reference_id,
            'status' => $new_status,
        ]);

        CanceledOrderJob::dispatch($order);
    }
}
