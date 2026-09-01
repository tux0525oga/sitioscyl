<?php

namespace App\Mail;

use App\Models\QuoteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteRequestReceived extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public QuoteRequest $quoteRequest
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: sprintf(
                'Nueva cotización %s | Somos Constructivos',
                $this->quoteRequest->folio
            )
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.quotes.received',
            with: [
                'adminUrl' => route(
                    'admin.quotes.show',
                    [
                        'quoteRequest' =>
                            $this->quoteRequest,
                    ]
                ),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}