<?php

namespace App\Http\Requests\variant_values;

use Illuminate\Foundation\Http\FormRequest;

class VariationValueRequest extends FormRequest
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
            'variation_id' => 'required',
            'name' =>'required',
            'type' => 'required|integer',
            'type_value' => 'required',
        ];
    }
}
