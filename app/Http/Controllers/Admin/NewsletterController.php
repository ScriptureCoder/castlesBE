<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendNewsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function all()
    {

        return;
    }

    public function send(Request $request)
    {
        $users= $request->target;
        $subject= $request->subject;
        $post= $request->html;

        //sending mail to each of the emails in the database
        foreach ($users as $user) {
            Mail::send( new SendNewsletter($user, $subject, $post, $request->target));
        }

        return response()->json([
            "status"=> true,
            "message"=> "Newsletter Sent Successfully!"
        ]);
    }


}
