<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $id;
    public function __construct($id)
    {
        $this->id= $id;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user= User::find($this->id);
        return $this->from(env("email"))
            ->view('emails.welcome', ['name' => $user->name, 'code' => $user->code])
            ->to($user->email)
            ->subject('Welcome to'.env("APP_NAME").'confirmation code');
    }
}
