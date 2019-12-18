<?php

namespace App\Http\Resources;

use App\Models\Country;
use App\Models\State;
use App\User;
use Illuminate\Http\Resources\Json\Resource;

class AgentsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $country = Country::find($this->country_id);
        $state = State::find($this->state_id);
        $user = User::find($this->id);

        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'username'=> $this->username,
            "image"=> $this->image_id? env("STORAGE") !== "local"? env("STORAGE_PATH")."".$user->image->path:url("/storage/".$user->image->path):"",
            'email'=> $this->email,
            'address'=> $this->address,
            'country'=> ['id'=>$this->country_id, 'name'=>$country?$country->name:""],
            'state'=> ['id'=>$this->state_id, 'name'=>$state?$state->name:""],
        ];
    }
}
