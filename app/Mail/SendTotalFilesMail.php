<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendTotalFilesMail extends Mailable
{
    use Queueable, SerializesModels;

    private $order_id;
    private $path;
    private $data;
    private $hora;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_id, $data)
    {
        $this->order_id = $order_id;
        $this->path = $data['path'];
        $this->data = $data['data'];
        $this->hora = $data['hora'];
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Etiqueta e romaneio disponíveis - Pedido '.$this->order_id,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.send-total-files',
            with: [
                'date' => $this->data,
                'hour' => $this->hora,
                'order_id' => $this->order_id
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            Attachment::fromPath(storage_path($this->path))
        ];
    }
}
