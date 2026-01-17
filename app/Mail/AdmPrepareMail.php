<?php

namespace App\Mail;

use App\Helpers\CouponHelper;
use App\Models\Complete;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AdmPrepareMail extends Mailable
{
    use Queueable, SerializesModels;

    private $order;
    private $user;
    private $complete;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
        $this->user = User::select('id', 'name', 'email')->where('id', $this->order->user)->first();
        $this->complete = Complete::select('id', 'tax_id', 'postal_code', 'street', 'number', 'locality', 'city', 'region_code', 'country')->where('user', $this->order->user)->first();
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: '#' . $this->order->id . ' Nova venda realizada, preparar pedido 🎉',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $products = json_decode($this->order->products);
        $coupon_details = CouponHelper::instance()->get_amount($this->order->coupon, $products);

        return new Content(
            markdown: 'emails.orders.adm-prepare-order',
            with: [
                'coupon' => $this->order->coupon,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'tax_id' => $this->complete->tax_id,
                'postal_code' => $this->complete->postal_code,
                'street' => $this->complete->street,
                'number' => $this->complete->number,
                'locality' => $this->complete->locality,
                'city' => $this->complete->city,
                'region_code' => $this->complete->region_code,
                'country' => $this->complete->country,
                'products' => $products,
                'amount' => $this->order->amount,
                'discounts' => $coupon_details['discount'],
            // 'complete' => $complete,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
