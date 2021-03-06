<?php

namespace App\Http\Controllers\Statics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class Mailer extends Controller
{
    public static function send($data){

        Mail::send('emails.email', $data, function ($message) use ($data) {
            $message->to($data['email'])->subject($data['subject']);
            $message->from(env("EMAIL"), env("APP_NAME"));
        });
    }
}
