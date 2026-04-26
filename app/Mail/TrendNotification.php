<?php

namespace App\Mail;

use Illuminate\Bus\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrendNotification extends Mailable
{
    use SerializesModels;

    public $trend;

    public function __construct($trend)
    {
        $this->trend = $trend;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tren Fashion Terbaru: ' . $this->trend->judul,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trend_notification', 
        );
    }
}