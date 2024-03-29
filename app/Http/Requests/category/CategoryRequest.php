<?php

namespace App\Http\Requests\category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name' => 'required|min:3',
            'sub_title'=>'required',
            'description' => 'required|min:11',
            'route' =>'required',
            'seo' => 'required'
        ];
    }
}
