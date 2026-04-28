<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BarterOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $title; // Pastikan ini ada

    public function __construct($otp, $title)
    {
        $this->otp = $otp;
        $this->title = $title;
    }

    public function build()
    {
        return $this->subject('[Gema Sandang] Kode Verifikasi Barter')
                    ->html("Kode OTP Anda adalah: <b>{$this->otp}</b>");
    }
}