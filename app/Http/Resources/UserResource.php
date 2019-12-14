<?php

namespace App\Http\Resources;

use App\Models\Country;
use App\Models\Image;
use App\Models\Role;
use App\Models\State;
use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image = Image::find($this->image_id);
        $country = Country::find($this->country_id);
        $state = State::find($this->state_id);

        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'username'=> $this->username,
            'phone'=> $this->phone,
            'whatsapp'=> $this->whatsapp,
            'bio'=> $this->bio,
            'email'=> $this->email,
            'image'=> $image? env("STORAGE") !== "local"? env("STORAGE_PATH")."".$image->path:url("/storage/".$image->path):"",
            'isVerified'=> $this->email_verified_at == !null,
            'role'=> ["id"=>$this->role_id, "name"=> Role::find($this->role_id)->name],
            'address'=> $this->address,
            'country'=> ['id'=>$this->country_id, 'name'=>$country?$country->name:""],
            'state'=> ['id'=>$this->state_id, 'name'=>$state?$state->name:""],
        ];
    }
}
