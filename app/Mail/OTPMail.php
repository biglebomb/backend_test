<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OTPMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $address = 'no-reply@backend.test';
        $subject = 'OTP Mail';
        $name = 'Jane Doe';

        return $this->view('mails.otp')
            ->from($address, $name)
//            ->cc($address, $name)
//            ->bcc($address, $name)
//            ->replyTo($address, $name)
            ->subject($subject)
            ->to($this->data->email)
            ->with('data', $this->data);
    }
}
