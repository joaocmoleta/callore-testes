<?php

namespace App\Http\Controllers;

use App\Models\Order;
use \Milon\Barcode\DNS2D;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class EncomendaController extends Controller
{
    public function printLabelTela($order_id, $volumes)
    {
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

        // dd($order);
        // dd($order_volumes);
        $d = new DNS2D();
        $d->setStorPath(__DIR__ . '/cache/');
        $data_mix = $d->getBarcodeHTML(json_decode($order->volumes)[0]->rota, 'DATAMATRIX', 3, 3);
        $datamix = [$data_mix];
        $navegador = [true];
        
        return view('dashboard.etiqueta-e-romaneio', compact('order', 'vol', 'datamix', 'navegador', 'order_volumes'));
    }

    public function printLabel($order_id, $volumes)
    {
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

        $d = new DNS2D();
        $d->setStorPath(__DIR__ . '/cache/');
        $data_mix = $d->getBarcodeHTML(json_decode($order->volumes)[0]->rota, 'DATAMATRIX', 3, 3);
        $datamix = [$data_mix];
        $pdf = Pdf::loadView('dashboard.etiqueta-e-romaneio', compact('order', 'vol', 'datamix'));
        $path = 'app/delivery/etiqueta-e-romaneio-' . $order->pedido . '-' . Carbon::now()->format('ymdHis') . '.pdf';
        $pdf->setPaper('a4', 'landscape')->save(storage_path($path));
        return $path;
    }

    public function printLabelOld($order_id)
    {
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

        // dd($order);
        $d = new DNS2D();
        $d->setStorPath(__DIR__ . '/cache/');
        $data_mix = $d->getBarcodeHTML(json_decode($order->volumes)[0]->rota, 'DATAMATRIX', 3, 3);
        $datamix = [$data_mix];
        return view('dashboard.etiqueta', compact('order', 'datamix'));
    }
}
