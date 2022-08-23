<?php

namespace App\Http\Requests\fabric;

use Illuminate\Foundation\Http\FormRequest;

class FabricRequest extends FormRequest
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
            'name' => 'required',
            'type' => 'required|min:3',
            'color_range' => 'required',
            'featured_img' =>'required',
            'finish' =>'required'
        ];
    }



}
