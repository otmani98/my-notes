<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $address = 'fc080b1342e0@drmail.in';
        $subject = 'Reset your password';
        $name = 'Yhwach';

        return $this->view('reset')
                    ->from($address, $name)
                    ->subject($subject)
                    ->with([ 'link' => $this->data['link']]);
    }
}