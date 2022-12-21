<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // DB::enableQueryLog();
        $products['products'] =
            Product::select('id', 'category_id','long_description' ,'name', 'route' ,'featured_image')
            ->with('category_route.parent_catetory:id,name,route')
            ->where('name', 'like', '%' . $request->get('query') . '%')
            //->Orwhere('long_description', 'like', '%' . $request->get('query') . '%')
            ->get();

        // return DB::getQueryLog();

        $products['category'] = Category::select('id','name', 'parent_id','route','featured_image')
        ->where('name', 'like', '%' . $request->get('query') . '%')->with('parent_catetory:id,name,route')->get();

        //return $products;
        return response()->json($products ,200);
    }
}



// [
//     'posts:id,title,user_id' => [
//         'comments:id,content,post_id' => [
//             'tags',
//         ],
//     ],
// ]


// [
//     // 'category:id,name,route,parent_id' => ['parent_catetory:id,name,route,parent_id']
//     'category:id,name,route,parent_id' => [
//         'parent_catetory:id,name,route,parent_id' => [
//             'products'
//         ]
//         ]
// ]