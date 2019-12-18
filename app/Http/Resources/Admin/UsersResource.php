<?php

namespace App\Http\Resources\Admin;

use App\Models\Country;
use App\Models\Image;
use App\Models\Role;
use App\Models\State;
use Illuminate\Http\Resources\Json\Resource;

class UsersResource extends Resource
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
            'email'=> $this->email,
            'phone'=> $this->phone,
            'image'=> $image? env("STORAGE") !== "local"? env("STORAGE_PATH")."".$image->path:url("/storage/".$image->path):"",
            'isVerified'=> $this->email_verified_at == !null,
            'role'=> ["id"=>$this->role_id, "name"=> Role::find($this->role_id)->name],
            'address'=> $this->address,
            'country'=> ['id'=>$this->country_id, 'name'=>$country?$country->name:""],
            'state'=> ['id'=>$this->state_id, 'name'=>$state?$state->name:""],
            'created_at'=> $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=> $this->updated_at->format('Y-m-d H:i:s'),
            'verified_at'=> !$this->email_verified_at?false:$this->email_verified_at->format('Y-m-d H:i:s'),
            'suspended_at'=> !$this->deleted_at?false:$this->deleted_at->format('Y-m-d H:i:s'),
        ];
    }
}
