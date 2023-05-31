<?php

namespace App\Http\Requests\product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            "name" => "required|min:3",
            "featured_image" => "required",
            "long_description" => "required|min:6",
            "category_id" => "required",
            "brand" => "required",
            // "download" => "required",
            // "variations" => "required",
            // "dimentions" => "required",
            "route" => "required",
            //"seo" => "required"
        ];
    }

}
