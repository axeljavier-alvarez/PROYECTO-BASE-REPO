<?php

namespace App\Mail;

use App\Models\Solicitante;
use App\Services\QrGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacionSolicitud extends Mailable
{
    use Queueable, SerializesModels;

    public string $url = '';
    public ?Solicitante $solicitante = null;
    public string $title = 'Mega Feria Empleo | MuniGuate';

    /**
     * Create a new message instance.
     */
    public function __construct(string $url, Solicitante $solicitante) {
        $this->url = $url;
        $this->solicitante = $solicitante;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('nvasquez@muniguate.com', 'Mega Feria Empleo | MuniGuate'),
            subject: 'Mega Feria Empleo | MuniGuate',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $codigo_qr = QrGenerator::generatePng($this->url, 300);

        return new Content(
            markdown: 'mails.layout',
            with: [
                'qrCode' => $codigo_qr,
            ],
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
