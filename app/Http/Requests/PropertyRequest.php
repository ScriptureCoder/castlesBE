<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
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
            'title' => 'required|string|max:225',
            'price' => 'integer',
            'description' => 'required|string',
            'status_id' => 'required|integer',
//            'type_id' => 'integer',
//            'bedrooms' => 'integer',
//            'bathrooms' => 'integer',
//            'toilets' => 'integer',
//            'parking' => 'integer',
//            'total_area' => 'integer',
//            'covered_area' => 'integer',
            'state_id' => 'required|integer',
            'locality_id' => 'required|integer',
            'address' => 'required|string',
//            'label_id' => 'integer',
        ];
    }
}
