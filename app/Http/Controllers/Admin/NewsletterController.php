<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Statics\Mailer;
use App\Mail\SendNewsletter;
use App\Models\Subscriber;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function send(Request $request)
    {
        switch ($request->target){
            case "subscribers":
                $users = Subscriber::all();
                break;
            case "agents":
                $users = User::where("role_id", 2)->get();
                break;
            case "individuals":
                $users = User::where("role_id", 1)->get();
                break;
            default:
                $users = Subscriber::all();
        }

        $subject= $request->subject;
        $post= $request->html;


        foreach ($users as $user) {
            $email = [
                "subject"=> $subject,
                'email' => $users->email,
                "html"=> $post
            ];

            Mailer::send($email);
        }

        return response()->json([
            "status"=> true,
            "message"=> "Newsletter Sent Successfully!"
        ]);
    }


}
