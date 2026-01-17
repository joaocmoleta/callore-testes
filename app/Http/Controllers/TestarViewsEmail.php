<?php

namespace App\Http\Controllers;

use App\Helpers\CouponHelper;
use App\Models\Coupon;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Log;

class TestarViewsEmail extends Controller
{
    public function index($id)
    {
        return $this->new_sale($id);
    }

    private function doublee($id)
    {
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.account.double', [
            'url' => route('orders.show_order', $id),
        ]);
    }

    private function double($id)
    {
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.account.double', [
            'url' => route('orders.show_order', $id),
        ]);
    }

    private function status($id)
    {
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.orders.status', [
            'url' => route('orders.show_order', $id),
            'status' => 'rota_de_entrega'
        ]);
    }

    private function entrega($id)
    {
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.orders.shipped', [
            'url' => route('orders.show_order', $id),
        ]);
    }

    private function preparar($id)
    {
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.orders.prepare-order', [
            'url' => route('orders.show_order', $id),
        ]);
    }

    private function pagamento_ordem($id)
    {
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.orders.paid-order', [
            'url' => route('orders.show_order', $id),
        ]);
    }

    private function ultima_etapa($id)
    {
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.orders.last-step', [
            'url' => route('orders.show_order', $id),
        ]);
    }

    private function ger_boleto($id)
    {
        $order = Order::select('*')
            ->where('id', $id)
            ->first();

        $payment = Payment::select('barcode', 'links')->where('id', $order->payment)->first();
        $links = json_decode($payment->links);

        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.orders.ger-boleto', [
            'id' => $id,
            'barcode' => $payment->barcode,
            'url' => $links[0]->href,
        ]);
    }

    private function cancel($id)
    {
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.orders.cancel-order', [
            'url' => route('orders.show_order', $id),
        ]);
    }

    private function estorno($id)
    {
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.orders.estorno-order', [
            'url' => route('orders.show_order', $id),
        ]);
    }

    private function new_sale($id)
    {
        $order = Order::select('*')->where('id', $id)
            ->first();

        // $coupon = CouponHelper::instance()->get($order->coupon);

        $products = json_decode($order->products);

        $coupon_details = CouponHelper::instance()->get_amount($order->coupon, $products);
        

        // Log::info($coupon_details);

        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.orders.adm-prepare-order', [
            'coupon' => $order->coupon,
            'name' => 'Fulano da Silva',
            'email' => 'fulano@dasilva.com.br',
            'phone' => '+5541998410336',
            'tax_id' => '08201165993',
            'postal_code' => '83030000',
            'street' => 'Rua Demétrio Zanão',
            'number' => '205',
            'locality' => 'Agaraú',
            'city' => 'São José dos Pinhais',
            'region_code' => 'PR',
            'country' => 'Brasil',
            'products' => $products,
            'amount' => $order->amount,
            'discounts' => $coupon_details['discount'],
        ]);
    }

    public function prazo_entrega($id)
    {
        $delivery = Delivery::select(
            'estimative'
        )->where('order', $id)
            ->first();

        // Log::info($delivery);

        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.orders.delivery-time', [
            'url' => route('orders.show_order', $id),
            'estimative' => $delivery->estimative
        ]);
    }

    public function confirmacaoTotal()
    {
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.send-total-files', ['date' => '08/10/2023', 'hour' => '13:10:49', 'order_id' => 5]);
    }

    public function newLead()
    {
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.new_lead', [
            'name' => 'Lucilia Tostes Agrifoglio',
            'phone' => '+5551992568183',
            'email' => 'luagrifoglio@gmail.com',
        ]);
    }
}
