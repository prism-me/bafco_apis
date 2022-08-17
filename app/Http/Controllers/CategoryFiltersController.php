<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redis;

class CategoryFiltersController extends Controller
{

    public function CategoryFilterList(Category $category){

        $redis = Redis::connection();
        $redis->del('category_filters_list1');

        if(!$redis->get('category_filters_list1')){

            // $brands = Product::distinct()->where('category_id',$category->id)->pluck('brand');
            $variations =  DB::select("CALL CategoryFilterList('". $category->route ."')");

            $redis->set('category_filters_list1', [ "value" => $variations ]);

            return response()->json(['category_filters_list1' => $variations] , 200);

        }else{
            return response()->json(["category_filters_list2" , $redis->get('category_filter_list')] ,200);


        }

        // $brands = Product::distinct()->where('category_id',$category->id)->pluck('brand');
        // $variations =  DB::select("CALL CategoryFilterList('". $category->route ."')");

        // return response()->json(['brands' => $brands , 'variations' => $variations] , 200);

    }

    public function CategoryListFilteration(Request $request){

        $brand = str_replace('-', ' ', $request->brand);
        $filtered =  DB::select("CALL CategoryFilterList('". $request->category ."')");

        $filtered->filter(function ($value, $key) {
            dd($value);
        });

        //return response()->json(['data' => $filtered] , 200);
    }




// $results = Order::when(!empty($request->startDate), function($q){
//     $start = Carbon::createFromFormat('Y-m-d', request('startDate'))->startOfDay();
//     $q->where('created_at','>=', $start);

// })
// ->when(!empty($request->endDate), function($q){
//     $end = Carbon::createFromFormat('Y-m-d', request('endDate'))->endOfDay();
//     $q->where('created_at', '<=', $end);
// })
// ->when(!empty($request->orderStatus), function($q){
//     $q->where('status', request('orderStatus'));
// })
// ->when(!empty($request->paymentMethod), function($q){
//     $q->where('paymentmethod', request('paymentMethod'));
// })
// ->when(count($request->all()) === 0, function($q){
//     return ['data'=>'No record found.','status'=>404];
// })->get();
// //->where('status', $request->orderStatus)->get();

// if($results->count() > 0){
//      return response($results , 200);
// }else{
//     return ['data'=>'No record found.','status'=>404];
// }



}
