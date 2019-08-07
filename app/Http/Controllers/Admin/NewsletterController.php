<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Statics\Mailer;
use App\Http\Resources\SubscriberResource;
use App\Mail\SendNewsletter;
use App\Models\Subscriber;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{

    public function subscribers(Request $request)
    {
        $data = Subscriber::paginate($request->paginate?$request->paginate:10);
        SubscriberResource::collection($data);

        return response()->json([
            "status"=> 1,
            "data"=> $data,
        ],200);

    }

    public function send(Request $request)
    {
        $request->validate([
            "subject"=> "required|string",
            "html"=> "required|string",
            "target"=> "required|string"
        ]);

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
                'email' => $user->email,
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
