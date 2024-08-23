<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class confirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject("Te registraste a Registro Docente")->view('mails.confirmationMail');
    }
}
