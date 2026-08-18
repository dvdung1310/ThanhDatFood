<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage) {}

    public function build(): self
    {
        $mail = $this->subject('Liên hệ mới từ '.$this->contactMessage->name)
            ->view('emails.contact-message');

        if ($this->contactMessage->email) {
            $mail->replyTo($this->contactMessage->email, $this->contactMessage->name);
        }

        return $mail;
    }
}
