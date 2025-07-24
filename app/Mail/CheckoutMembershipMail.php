<?php

namespace App\Mail;

use App\Models\Product;
use App\Models\UserProduk;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CheckoutMembershipMail extends Mailable
{
    use Queueable, SerializesModels;

    public $id;
    public $data;
    public $product;
    /**
     * Create a new message instance.
     */
    public function __construct($id)
    {
     
        $this->data = UserProduk::with('user')->find( $id);
        $this->product = Product::with('classes')->find($this->data->product_id);
      
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice payment',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.invoice',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
