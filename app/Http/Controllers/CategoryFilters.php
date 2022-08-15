<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use DB;

class CategoryFilters extends Controller
{
    
    public function CategoryFilterList(Category $category){

        $brands = Product::distinct()->where('category_id',$category->id)->pluck('brand');
        $variations =  DB::select("CALL CategoryFilterList('". $category->route ."')");

        return response()->json(['brands' => $brands , 'variations' => $variations] , 200);


    }
}
