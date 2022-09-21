<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


#Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
#     return $request->user();
#});

#search
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'search']);

#Categories
Route::get('categories', 'CategoryController@index');
Route::post('categories', 'CategoryController@store')->middleware('auth:sanctum');
Route::get('categories/{category}', 'CategoryController@show');
Route::delete('categories/{category}', 'CategoryController@destroy')->middleware('auth:sanctum');
Route::get('frontpage_category/{route}', 'CategoryController@frontpage_category');
Route::get('sub-category', 'CategoryController@subCategory');


#Products
Route::get('products', 'ProductController@index');
Route::post('products', 'ProductController@store')->middleware('auth:sanctum');
Route::get('products/{id}', 'ProductController@show');
Route::put('change-status/{id}', 'ProductController@changeStatus')->middleware('auth:sanctum');
Route::get('disable-products', 'ProductController@disableProducts');
Route::delete('delete-product-variation/{id}', 'ProductController@deleteProductVariation')->middleware('auth:sanctum');
Route::put('clone-product-variation/{id}' , 'ProductController@cloneVariation')->middleware('auth:sanctum');




#Variations
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


#Team
Route::get('project-category', 'ProjectCategoryController@index');
Route::post('project-category', 'ProjectCategoryController@store')->middleware('auth:sanctum');
Route::get('project-category/{ProjectCategory}', 'ProjectCategoryController@show');
Route::delete('project-category/{ProjectCategory}', 'ProjectCategoryController@destroy')->middleware('auth:sanctum');

#Partner
Route::get('partners', 'PartnerController@index');
Route::post('partners', 'PartnerController@store')->middleware('auth:sanctum');
Route::get('partners/{partner}', 'PartnerController@show');
Route::delete('partners/{partner}', 'PartnerController@destroy')->middleware('auth:sanctum');

#Contact Us
Route::post('form-submit', 'ContactUsController@store');
Route::get('contact-us', 'ContactUsController@index');
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

#Variation values
Route::get('variation_values', 'VariationValueController@index');
Route::post('variation_values', 'VariationValueController@store')->middleware('auth:sanctum');
Route::get('variation_values/{variation_value}', 'VariationValueController@show');
Route::delete('variation_values/{variation_value}', 'VariationValueController@destroy')->middleware('auth:sanctum');

#Upload
Route::post('uploads', 'UploadController@upload_media');
Route::post('upload-files', 'UploadController@files');
Route::get('uploads', 'UploadController@get_all_images');
Route::delete('uploads/{upload}', 'UploadController@delete_images');

#Video
Route::get('videos', 'VideoController@index');
Route::post('videos', 'VideoController@store')->middleware('auth:sanctum');
Route::get('videos/{video}', 'VideoController@show');
Route::delete('videos/{video}', 'VideoController@destroy')->middleware('auth:sanctum');

#Promo Codes
Route::get('promo-codes', 'PromoCodeController@index');
Route::post('promo-codes', 'PromoCodeController@store')->middleware('auth:sanctum');
Route::get('promo-codes/{id}', 'PromoCodeController@show');
Route::delete('promo-codes/{id}', 'PromoCodeController@destroy')->middleware('auth:sanctum');

#Project

Route::get('projects', 'ProjectController@index');
Route::get('project-product', 'ProjectController@projectProduct');
Route::post('projects', 'ProjectController@store')->middleware('auth:sanctum');
Route::get('projects/{project}', 'ProjectController@show');
Route::delete('projects/{project}', 'ProjectController@destroy')->middleware('auth:sanctum');

#Fabric
Route::get('materials ', 'MaterialController@index');
Route::post('materials', 'MaterialController@store')->middleware('auth:sanctum');
Route::get('materials/{id}', 'MaterialController@show');
Route::delete('materials/{id}', 'MaterialController@destroy')->middleware('auth:sanctum');

#Finishes
Route::get('finishes-category-list', 'FinishesController@finishesCategoryList');
Route::get('material-list', 'FinishesController@materialList');

Route::get('finishes', 'FinishesController@index');
Route::post('finishes', 'FinishesController@store')->middleware('auth:sanctum');
Route::get('finishes/{id}', 'FinishesController@show');
Route::delete('finishes/{id}', 'FinishesController@destroy')->middleware('auth:sanctum');

#Brochures
Route::get('brochures', 'BrochureController@index');
Route::post('brochures', 'BrochureController@store')->middleware('auth:sanctum');
Route::get('brochures/{id}', 'BrochureController@show');
Route::delete('brochures/{id}', 'BrochureController@destroy')->middleware('auth:sanctum');


#Plan
Route::get('plans', 'PlanController@index');
Route::post('plans', 'PlanController@store')->middleware('auth:sanctum');
Route::get('plans/{id}', 'PlanController@show');
Route::delete('plans/{id}', 'PlanController@destroy')->middleware('auth:sanctum');


#Cart
Route::get('addresses/{id}', 'AddressController@index')->middleware('auth:sanctum');
Route::get('address-detail/{id}', 'AddressController@show')->middleware('auth:sanctum');

Route::post('addresses', 'AddressController@store')->middleware('auth:sanctum');
Route::delete('addresses/{address}', 'AddressController@destroy')->middleware('auth:sanctum');
Route::put('set-default/{id}', 'AddressController@setDefault')->middleware('auth:sanctum');


#Payment
Route::post('/checkout', [PaymentController::class, 'checkout']);
Route::post('/guestCheckout', [PaymentController::class, 'guestCheckout']);
Route::post('/authCheckout', [PaymentController::class, 'authCheckout']);
Route::get('/paymentSuccess', [PaymentController::class, 'successResponse']);
Route::get('/paymentFailed', [PaymentController::class, 'failedResponse']);

#Front Controllers

/*Product Inner page*/

Route::get('front-products/{route}', 'FrontProductController@frontProducts');
Route::get('product-detail/{route}/{id?}', 'FrontProductController@productDetail');
Route::get('home-product-category-filter/{route}', 'FrontProductController@homeProductCategoryFilter');
Route::get('product-filter-data', 'FrontProductController@filterProductData');
Route::get('related-products/{route}', 'FrontProductController@relatedProducts');
Route::get('random-products', 'FrontProductController@randomProducts');


/*End Product Inner Page*/

/*Resource Front Page*/

Route::get('home-resource', 'FrontResourceController@index');

#Videos
Route::get('front-videos', 'FrontResourceController@frontVideos');


#Projects
Route::get('all-project/{type}', 'FrontResourceController@allProject');
Route::get('project-category-list', 'FrontResourceController@projectCategoryList');
Route::get('project-detail/{id}', 'FrontResourceController@projectDetail');

#Brouchers
Route::get('brochure-category-list', 'FrontResourceController@brochureCategoryList');
Route::get('brochure-filter/{type}', 'FrontResourceController@brochuresFilter');
Route::get('brochures-detail/{id}', 'FrontResourceController@brochuresDetail');

#Plans
Route::get('plan-category-list', 'FrontResourceController@planCategoryList');
Route::get('plan-filter/{type}', 'FrontResourceController@planFilter');
Route::get('plan-detail/{id}', 'FrontResourceController@planDetail');

#Plans
Route::get('finishes-filter-list/{type}', 'FrontResourceController@finishesFilterList');
Route::post('finishes-filter-data', 'FrontResourceController@finishesFilterData');
Route::get('finishes-filter-detail/{id}', 'FrontResourceController@finishesFilterDetail');


/* End Resource Front Page*/


#Category Filters List

Route::get('category-filters-list/{category}', 'CategoryFiltersController@CategoryFilterList');
Route::post('category-list-filteration', 'CategoryFiltersController@CategoryListFilteration');


#Product Detail
Route::get('front-category/{route}', 'FrontProductController@category');
Route::get('header-category', 'FrontProductController@headerCategory');
Route::get('top-selling-products', 'FrontProductController@topSellingProduct');


#Guest Cart

Route::get('guest-cart/{id}', 'GuestCartController@index');
Route::post('guest-cart', 'GuestCartController@store');
Route::delete('guest-remove-cart/{id}', 'GuestCartController@removeCart');
Route::delete('guest-clear-all-cart/{id}', 'GuestCartController@clearAllCart');
Route::post('guest-cart-qty', 'GuestCartController@incrementQty');
Route::get('guest-cart-detail/{id}', 'GuestCartController@show');
Route::get('guest-cart-total/{id}', 'GuestCartController@cartTotal');


#Front Apis
Route::get('home', 'FrontController@home');
Route::get('about', 'FrontController@about');
Route::get('contact-us', 'FrontController@contactUs');
Route::get('top-management', 'FrontController@topManagement');
Route::get('services', 'FrontController@services');
Route::get('innovations', 'FrontController@innovations');
Route::get('faq', 'FrontController@faq');


#Forms Submission
Route::post('enquiries', 'EnquiryController@store');
Route::get('enquiries', 'EnquiryController@index');
Route::get('enquiries/{id}', 'EnquiryController@show');
Route::delete('enquiries/{id}', 'EnquiryController@destroy');





#Forgot Password
Route::post('forget-password', 'UserController@forgetPassword');

#Order Detail User
Route::get('user-order-detail/{id}', 'UserOrderDetailController@userOrderDetail');


Route::group(['prefix' => 'auth'], function ($router) {

    Route::post('/register', 'UserController@register');
    Route::post('/email-verification', 'UserController@emailVerify');

    Route::post('/login', 'UserController@login');
    Route::get('/me', 'UserController@me')->middleware('auth:sanctum');
    Route::post('/logout', 'UserController@logout')->middleware('auth:sanctum');
    Route::post('/update-profile', 'UserController@updateProfile')->middleware('auth:sanctum');
    Route::post('/change-password', 'UserController@changePassword')->middleware('auth:sanctum');
    Route::get('/track-order/{id}', 'UserController@trackOrder')->middleware('auth:sanctum');




    #Wishlist
    Route::get('wishlists/{id}', 'WishlistController@index')->middleware('auth:sanctum');
    Route::get('wishlists/{id}', 'WishlistController@index')->middleware('auth:sanctum');
    Route::get('wishlist-detail/{id}', 'WishlistController@show')->middleware('auth:sanctum');
    Route::post('wishlists', 'WishlistController@store')->middleware('auth:sanctum');
    Route::delete('wishlists/{wishlist}', 'WishlistController@removeWishlist')->middleware('auth:sanctum');

    #Promo Check
    Route::post('promo-check', 'PromoUserController@promoCheck')->middleware('auth:sanctum');

    #Cart
    Route::get('cart/{id}', 'CartController@index')->middleware('auth:sanctum');
    Route::post('cart', 'CartController@store')->middleware('auth:sanctum');
    Route::delete('remove-cart/{id}', 'CartController@removeCart')->middleware('auth:sanctum');
    Route::delete('clear-all-cart/{id}', 'CartController@clearAllCart')->middleware('auth:sanctum');
    Route::post('cart-qty', 'CartController@incrementQty')->middleware('auth:sanctum');
    Route::get('cart-detail/{id}', 'CartController@show')->middleware('auth:sanctum');
    Route::get('cart-total/{id}', 'CartController@cartTotal')->middleware('auth:sanctum');
    Route::get('promo-cart-total/{id}', 'CartController@promoCartDetail')->middleware('auth:sanctum');
    Route::post('get-guest-cart', 'CartController@getGuestCart')->middleware('auth:sanctum');



    #Dashboard CMS

    Route::get('dashboard-details', 'DashboardController@dashboardDetails');
    Route::get('all-users', 'DashboardController@allUsers');
    Route::get('all-orders', 'DashboardController@allOrder');
    Route::get('order-detail/{id}', 'DashboardController@orderDetail');
    Route::post('change-order-status', 'DashboardController@changeOrderStatus');
    Route::get('product-report-list', 'DashboardController@productReportList');
    Route::get('product-report-detail/{id}', 'DashboardController@productReportDetail');
    Route::get('transactions', 'DashboardController@transaction');
    Route::post('transaction-filter', 'DashboardController@transactionFilter');
    Route::get('sales-list', 'DashboardController@salesList');
    Route::get('sales-count', 'DashboardController@salesCount');

    #Todo
    Route::get('todos', 'TodoController@index')->middleware('auth:sanctum');
    Route::post('todos', 'TodoController@store')->middleware('auth:sanctum');
    Route::get('todos/{id}', 'TodoController@show')->middleware('auth:sanctum');
    Route::delete('todos/{id}', 'TodoController@destroy')->middleware('auth:sanctum');
});

Route::fallback(function () {
    return response()->json(['message' => 'Invalid    Route'], 400);
});
