<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewsletter extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user, $subject, $post, $type;

    public function __construct($user, $subject, $post)
    {
        $this->user= $user;
        $this->subject= $subject;
        $this->post= $post;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('EMAIL'), env('APP_NAME'))
            ->view('emails.newsletter', ['name' => $this->user->name, 'html' => $this->post])
            ->to($this->user->email)
            ->subject( $this->subject );
    }
}
