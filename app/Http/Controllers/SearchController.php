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
        $products =
            Product::select('id', 'category_id', 'name', 'route')
            ->with('category_route.parent_catetory:id,name,route')
            ->where('name', 'like', '%' . $request->get('query') . '%')
            ->get();

        // return DB::getQueryLog();

        // $category = Category::select('name', 'route')->where('name', 'like', '%' . $request->get('query') . '%')->get();

        return response()->json($products, 200);
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