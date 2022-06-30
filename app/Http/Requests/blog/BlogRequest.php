<?php

namespace App\Http\Requests\blog;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'featured_image' => 'url',
            'description' => 'required',
            'short_description' => 'required',
            'route' =>'required',
            'tags' =>'required',
            'blog_type' =>'required',
         
        ];
    }
}
