<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SaveOrder extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected $order;

    /**
     * Create a new message instance.
     * @param $order
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('csu-construction-equipment@yandex.ru', 'csu-construction-equipment'),
            subject: 'Заказ оформлен',
        );
    }

    /**
     * Get the message content definition.
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order',
            with: [
                "order" => $this->order,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
