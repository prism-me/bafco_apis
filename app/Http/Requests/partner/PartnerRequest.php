<?php

namespace App\Http\Requests\partner;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
{
   
    public function authorize()
    {
        return true;
    }

   
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'route' =>'required'
        ];
    }
}
