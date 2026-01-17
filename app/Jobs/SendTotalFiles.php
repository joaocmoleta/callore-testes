<?php

namespace App\Jobs;

use App\Mail\SendTotalFilesMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use \Milon\Barcode\DNS2D;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SendTotalFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order_id;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info(static::class . ' linha ' . __LINE__ . ' Enviando etiqueta e romaneio.');

        $data = $this->printLabel($this->order_id, 1); // Gera o PDF com etiqueta e romaneio

        if(env('DEBUG_MOL')) {
            if(env('MAIL_SUPPORT')) {
                Mail::to(env('MAIL_SUPPORT'))->send(new SendTotalFilesMail($this->order_id, $data));
                Log::info(static::class . ' linha ' . __LINE__ . ' E-mail cópia: ' . env('MAIL_SUPPORT'));
            }
            return;
        }

        if(env('MAIL_ADM')) {
            Log::info(static::class . ' linha ' . __LINE__ . ' E-mail administrativo: ' . env('MAIL_ADM'));
            Mail::to(env('MAIL_ADM'))->send(new SendTotalFilesMail($this->order_id, $data));
        }
    }
    
    private function printLabel($order_id, $volumes) {
        $order = Order::select(
            'users.name',
            'completes.street',
            'completes.number',
            'completes.complement',
            'completes.locality',
            'completes.city',
            'completes.region_code',
            'completes.postal_code',
            'encomendas.volumes',
            'encomendas.pedido',
            'encomendas.data',
            'encomendas.hora',
            'invoices.numero',
        )
            ->leftJoin('invoices', 'invoices.order', '=', 'orders.id')
            ->leftJoin('users', 'orders.user', '=', 'users.id')
            ->leftJoin('completes', 'completes.user', '=', 'orders.user')
            ->leftJoin('encomendas', 'encomendas.order', '=', 'orders.id')
            ->where('orders.id', $order_id)
            ->first();

        $vol = [$volumes];
        $order_volumes = json_decode($order->volumes);

        $d = new DNS2D();
        $d->setStorPath(__DIR__ . '/cache/');
        $data_mix = $d->getBarcodeHTML(json_decode($order->volumes)[0]->rota, 'DATAMATRIX', 3, 3);
        $datamix = [$data_mix];
        $navegador = [false];
        $pdf = Pdf::loadView('dashboard.etiqueta-e-romaneio', compact('order', 'vol', 'datamix', 'navegador', 'order_volumes'));
        $path = 'app/delivery/etiqueta-e-romaneio-' . $order->pedido . '-' . Carbon::now()->format('ymdHis') . '.pdf';
        $pdf->setPaper('a4', 'landscape')->save(storage_path($path));

        Log::info(static::class . ' linha ' . __LINE__ . " Gerado documentos, informações: $path, $order->data, $order->hora");

        return [
            'path' => $path,
            'data' => $order->data,
            'hora' => $order->hora
        ];
    }
}
