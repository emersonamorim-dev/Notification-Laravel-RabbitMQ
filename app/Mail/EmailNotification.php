<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $message;

    public function __construct($subject, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->from('seu-email@seu-dominio.com', 'Nome Remetente')
                    ->view('emails.notification') // Aqui você pode criar uma view específica para o corpo do e-mail
                    ->with('message', $this->message);
    }
}

