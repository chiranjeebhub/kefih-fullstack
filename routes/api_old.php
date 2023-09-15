<?php

use Illuminate\Http\Request;
header('Access-Control-Allow-Origin: *');
//Access-Control-Allow-Origin: *
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');
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
Route::post('/test','API\ProductController@test');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
    Route::post('/getProfileImage','API\CustomerController@getProfileImage');
    Route::post('/setProfileImage','API\CustomerController@setProfileImage');
    
Route::post('/signup','API\CustomerController@signup');
Route::post('/login','API\CustomerController@login');
Route::post('/verify_otp','API\CustomerController@verify');
Route::post('/resend_otp','API\CustomerController@resend_otp');
Route::post('/forgot_password','API\CustomerController@forgot_password');
Route::post('/update_password','API\CustomerController@update_password');
Route::post('/updateProfile','API\CustomerController@updateProfile');
Route::post('/userProfile','API\CustomerController@userProfile');
Route::post('/changePassword','API\CustomerController@changePassword');
Route::post('/contactUs','API\CustomerController@contactUs');

Route::post('/banners','API\BannerController@banner_listing');
Route::post('/category','API\CategoryController@category_listing');
Route::any('/childCat','API\CategoryController@child_cat');

Route::post('/homeProduct','API\ProductController@home_product_listing');
Route::post('/product','API\ProductController@product_listing');
Route::post('/filters','API\ProductController@filters');
Route::post('/filters_value','API\ProductController@filters_value');
Route::post('/filterProduct','API\ProductController@product_listing_filter');
Route::post('/brand','API\ProductController@brand_listing');

Route::post('/productdetail','API\ProductController@product_detail');
Route::get('/productDesc/{type}/{id}','API\ProductController@productDesc')->name('productDesc');
Route::post('/get_dependend_color','API\ProductController@get_dependend_color');
Route::post('/coloredImages','API\ProductController@coloredImages');
Route::post('/productReview','API\ProductController@product_review_listing');
Route::post('/checkPinCode','API\ProductController@checkPinCode');
Route::post('/more_seller','API\ProductController@more_seller');

Route::post('/recentlyViewd','API\ProductController@recentlyViewd');
Route::post('/similarProduct','API\ProductController@similar_product_listing');
Route::post('/frequentPurchasedProduct','API\ProductController@frequent_purchase_product_listing');

Route::post('/cart','API\CartController@cart_listing');
Route::post('/cart_add_update','API\CartController@cart_add_update');

Route::post('/wishlist','API\WishlistController@wishlist_listing');
Route::post('/wishlist_add_update','API\WishlistController@wishlist_add_update');

Route::post('/applyCoupon','API\CheckOutController@applyCoupon');
Route::post('/saveOrder','API\CheckOutController@save_order');

//Checkout Shipping 
Route::post('/userAddresss','API\CustomerController@userAddresss');
Route::post('/addAddress','API\CustomerController@addAddress');
Route::post('/updateAddress','API\CustomerController@updateAddress');
Route::post('/deleteAddress','API\CustomerController@deleteAddress');
Route::post('/addressDetails','API\CustomerController@addressDetails');


Route::post('/wallet','API\WalletController@listing');
Route::any('/myReviews','API\ProductController@myReviews')->name('myReviews');
Route::any('/postReview','API\ProductController@postReview')->name('postReview');
Route::any('/postSellerRating','API\ProductController@postsellerRating')->name('postsellerRating');

Route::any('/getReason','API\OrderController@getReason')->name('getReason');
Route::any('/cancel_return_order','API\OrderController@cancel_return_order')->name('cancel_return_order');

Route::post('/order_list','API\OrderController@order_listing');
Route::post('/order_detail','API\OrderController@orderdetail');
Route::any('/order_invoice/{fld_order_id}','API\OrderController@order_invoice');


Route::post('/compareList','API\CompareController@compareList');
Route::post('/addRemoveCompareProduct','API\CompareController@addRemoveCompareProduct');
Route::post('/brandsList','API\CompareController@brandsList');
Route::post('/brandsWiseProduct','API\CompareController@brandsWiseProduct');
Route::post('/testproduct_detail','API\ProductController@testproduct_detail');




