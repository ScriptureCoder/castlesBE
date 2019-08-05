<?php

namespace App\Http\Resources;

use App\Models\Role;
use App\User;
use Illuminate\Http\Resources\Json\Resource;

class SubscriberResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::where('email', $this->email)->first();
        return [
            'id'=> $this->id,
            'email'=> $this->email,
            'user'=> $user?[
               'name'=> $user->name,
                'role'=> [
                    "id"=>$user->role_id,
                    "name"=> Role::find($user->role_id)->name
                ],

            ]:""
        ];
    }
}
