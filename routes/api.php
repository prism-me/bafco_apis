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

# Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
#     return $request->user();
# });

#categories
Route::get('categories' , 'CategoryController@index');
Route::post('categories' , 'CategoryController@store')->middleware('auth:sanctum');
Route::get('categories/{category}' , 'CategoryController@show');
Route::delete('categories/{category}' , 'CategoryController@destroy')->middleware('auth:sanctum');
Route::get('frontpage_category/{route}' , 'CategoryController@frontpage_category');

#products
Route::get('products' , 'ProductController@index');
Route::post('products' , 'ProductController@store')->middleware('auth:sanctum');
Route::get('products/{product}' , 'ProductController@show');
// Route::delete('products/{product}' , 'ProductController@destroy')->middleware('auth:sanctum');

#variations
Route::get('variations', 'VariationController@index');
Route::post('variations', 'VariationController@store')->middleware('auth:sanctum');
Route::get('variations/{variation}', 'VariationController@show');
Route::delete('variations/{variation}', 'VariationController@destroy')->middleware('auth:sanctum');

#Pages
Route::get('pages', 'PageController@index');
Route::post('pages', 'PageController@store')->middleware('auth:sanctum');
Route::get('pages/{page}', 'PageController@show');
Route::delete('pages/{page}', 'PageController@destroy')->middleware('auth:sanctum');

#Faq
Route::get('faqs', 'FaqController@index');
Route::post('faqs', 'FaqController@store')->middleware('auth:sanctum');
Route::get('faqs/{faq}', 'FaqController@show');
Route::delete('faqs/{faq}', 'FaqController@destroy')->middleware('auth:sanctum');

#Blog
Route::get('blogs', 'BlogController@index');
Route::post('blogs', 'BlogController@store')->middleware('auth:sanctum');
Route::get('blogs/{blog}', 'BlogController@show');
Route::delete('blogs/{blog}', 'BlogController@destroy')->middleware('auth:sanctum');

#Team
Route::get('teams', 'TeamController@index');
Route::post('teams', 'TeamController@store')->middleware('auth:sanctum');
Route::get('teams/{team}', 'TeamController@show');
Route::delete('teams/{team}', 'TeamController@destroy')->middleware('auth:sanctum');

#Partner
Route::get('partners', 'PartnerController@index');
Route::post('partners', 'PartnerController@store')->middleware('auth:sanctum');
Route::get('partners/{partner}', 'PartnerController@show');
Route::delete('partners/{partner}', 'PartnerController@destroy')->middleware('auth:sanctum');

#Contact Us
Route::get('contact-us', 'ContactUsController@index');
Route::post('contact-us', 'ContactUsController@store');
Route::get('contact-us/{contactUs}', 'ContactUsController@show');
Route::delete('contact-us/{contactUs}', 'ContactUsController@destroy')->middleware('auth:sanctum');

#Management
Route::get('managements', 'ManagementController@index');
Route::post('managements', 'ManagementController@store')->middleware('auth:sanctum');
Route::get('managements/{management}', 'ManagementController@show');
Route::delete('managements/{management}', 'ManagementController@destroy')->middleware('auth:sanctum');

#Testimonial
Route::get('testimonials', 'TestimonialController@index');
Route::post('testimonials', 'TestimonialController@store')->middleware('auth:sanctum');
Route::get('testimonials/{testimonial}', 'TestimonialController@show');
Route::delete('testimonials/{testimonial}', 'TestimonialController@destroy')->middleware('auth:sanctum');

#variation values
Route::get('variation_values', 'VariationValueController@index');
Route::post('variation_values', 'VariationValueController@store')->middleware('auth:sanctum');
Route::get('variation_values/{variation_value}', 'VariationValueController@show');
Route::delete('variation_values/{variation_value}', 'VariationValueController@destroy')->middleware('auth:sanctum');

#upload 
Route::post('uploads','UploadController@upload_media');
Route::get('uploads','UploadController@get_all_images');
Route::delete('uploads/{upload}','UploadController@delete_images');

#Video
Route::get('videos', 'VideoController@index');
Route::post('videos', 'VideoController@store')->middleware('auth:sanctum');
Route::get('videos/{video}', 'VideoController@show');
Route::delete('videos/{video}', 'VideoController@destroy')->middleware('auth:sanctum');


#Front Controllers
Route::get('home', 'FrontController@home');
Route::get('about', 'FrontController@about');
Route::get('contact-us', 'FrontController@contactUs');
Route::get('top-management', 'FrontController@topManagement');
Route::get('services', 'FrontController@services');
Route::get('innovations', 'FrontController@innovations');

#Forget Password
Route::post('forget-password', 'UserController@forgetPassword');
Route::post('submit-reset-password', 'UserController@submitResetPassword');

Route::group(['prefix' => 'auth'], function ($router) {
    
        Route::post('/register', 'UserController@register');
        Route::post('/email-verify', 'UserController@verifyEmail');
        Route::post('/login', 'UserController@login');
        Route::get('/me','UserController@me')->middleware('auth:sanctum');
        Route::post('/logout', 'UserController@logout')->middleware('auth:sanctum');
        Route::post('/reset', 'UserController@reset')->middleware('auth:sanctum'); 
    

        # User Detail
        Route::post('reset', 'UserController@reset'); 
        Route::get('user-detail', 'UserController@userDetail'); 
        Route::post('update-detail', 'UserController@updateUser'); 

        #Wishlist
        Route::get('wishlists', 'WishlistController@index')->middleware('auth:sanctum');
        Route::post('wishlists', 'WishlistController@store')->middleware('auth:sanctum');
        Route::get('wishlists/{id}', 'WishlistController@show')->middleware('auth:sanctum');
        Route::delete('wishlists/{id}', 'WishlistController@destroy')->middleware('auth:sanctum');

    });

Route::fallback(function () {
    return response()->json(['message'=>'Invalid Route'] , 400);
});











