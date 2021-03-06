<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//categories
Route::get('categories' , 'CategoryController@index');
Route::post('categories' , 'CategoryController@store')->middleware('auth:sanctum');
Route::get('categories/{category}' , 'CategoryController@show');
Route::delete('categories/{category}' , 'CategoryController@destroy')->middleware('auth:sanctum');
Route::get('frontpage_category/{route}' , 'CategoryController@frontpage_category');

//products
Route::get('products' , 'ProductController@index');
Route::post('products' , 'ProductController@store')->middleware('auth:sanctum');
Route::get('products/{product}' , 'ProductController@show');
Route::delete('products/{product}' , 'ProductController@destroy')->middleware('auth:sanctum');

//variations
Route::get('variations', 'VariationController@index');
Route::post('variations', 'VariationController@store')->middleware('auth:sanctum');
Route::get('variations/{variation}', 'VariationController@show');
Route::delete('variations/{variation}', 'VariationController@destroy')->middleware('auth:sanctum');

//variation values
Route::get('variation_values', 'VariationValueController@index');
Route::post('variation_values', 'VariationValueController@store')->middleware('auth:sanctum');
Route::get('variation_values/{variation_value}', 'VariationValueController@show');
Route::delete('variation_values/{variation_value}', 'VariationValueController@destroy')->middleware('auth:sanctum');

//upload 
Route::post('uploads','UploadController@upload_media');
Route::get('uploads','UploadController@get_all_images');
Route::delete('uploads/{upload}','UploadController@delete_images');

Route::group(['prefix' => 'auth'], function ($router) {

    Route::post('/register', 'UserController@register');
    Route::post('/login', 'UserController@login');
    Route::get('/me','UserController@me')->middleware('auth:sanctum');
    Route::post('/logout', 'UserController@logout')->middleware('auth:sanctum');

});

Route::fallback(function () {
    return response()->json(['message'=>'Invalid Route'] , 400);
});










