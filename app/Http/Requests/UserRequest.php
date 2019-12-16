<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|alpha_dash|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:25',
            'whatsapp' => 'string|max:25',
            'country_id' => 'integer',
            'state_id' => 'integer',
            'address' => 'string|max:255',
            'bio' => 'max:1000',
        ];
    }
}
