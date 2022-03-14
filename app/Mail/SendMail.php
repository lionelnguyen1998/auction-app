<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'AUCTIONでお間に合わせがありました';
      
        return $this->view('mail.sendmail', [
            'contacts' => $this->contact,
            ])
            ->from($this->contact['email'])
            ->subject($subject)
            ->with('contact', $this->contact);
    }
}
