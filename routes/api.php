<?php
use Illuminate\Http\Request;

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
Route::get('/test','API\NotificationController@test');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
    Route::post('/getProfileImage','API\CustomerController@getProfileImage');
    Route::post('/setProfileImage','API\CustomerController@setProfileImage');
    
    
    Route::group(['middleware' => ['cors']], function () {
    Route::post('/testLogin','API\CustomerController@testLogin'); 
    });

Route::post('/jlogin','API\CustomerController@jlogin');    
Route::post('/otp_login','API\CustomerController@OTPlogin');  

Route::post('/getprofileotp','API\CustomerController@getProfileOTP');
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

Route::post('/userWalletAmt','API\CustomerController@userWalletAmt');

Route::post('/banners','API\BannerController@banner_listing');
Route::post('/advertisement','API\AdvertisementController@advertisement_listing');
Route::post('/category','API\CategoryController@category_listing');

Route::post('/featuredcategory','API\CategoryController@featuredcategory_listing');
Route::post('/featuredcategory1','API\CategoryController@featuredcategory_listing1');
Route::post('/childCat','API\CategoryController@child_cat');
Route::post('/subcat','API\CategoryController@subcat_list');
Route::post('/offerzoneCat','API\CategoryController@category_offer_listing');
Route::post('/offerzoneProduct','API\ProductOfferController@cat_offer_wise');

Route::post('/homeProduct','API\ProductController@home_product_listing');
Route::post('/product','API\ProductController@product_listing');
Route::post('/extraproduct','API\ExtraController@product_listing');
Route::post('/filters','API\ProductController@filters');
Route::post('/filters2','API\ProductController@filters2');
Route::post('/filters_value','API\ProductController@filters_value');
Route::post('/filters_value2','API\ProductController@filters_value2');
Route::post('/filterProduct','API\ProductController@product_listing_filter');
Route::post('/brand','API\ProductController@brand_listing');

Route::post('/productdetail','API\ProductController@product_detail');
Route::get('/productDesc/{type}/{id}','API\ProductController@productDesc')->name('productDesc');
Route::post('/get_dependend_color','API\ProductController@get_dependend_color');
Route::post('/get_dependend_size','API\ProductController@get_dependend_size');
Route::post('/coloredImages','API\ProductController@coloredImages');
Route::post('/productReview','API\ProductController@product_review_listing');

Route::post('/productsearch','API\ProductController@product_search');

Route::post('/dummycheckPinCode','API\ProductController@dummycheckPinCode');
Route::post('/checkPinCode','API\ProductController@checkPinCode');
Route::post('/more_seller','API\ProductController@more_seller');

Route::post('/recentlyViewd','API\ProductController@recentlyViewd');
Route::post('/similarProduct','API\ProductController@similar_product_listing');
Route::post('/frequentPurchasedProduct','API\ProductController@frequent_purchase_product_listing');

Route::post('/cart','API\CartController@cart_listing');

Route::post('/shipping_charges','API\CartController@shipping_charges');
Route::post('/cart_add_update','API\CartController@cart_add_update');

Route::post('/wishlist','API\WishlistController@wishlist_listing');
Route::post('/wishlist_add_update','API\WishlistController@wishlist_add_update');

Route::post('/savelater','API\SavelaterController@savelater_listing');
Route::post('/savelater_add_update','API\SavelaterController@savelater_add_update');
Route::post('/validateCartProducts','API\CartController@validateCartProducts');
Route::post('/validateBuyProducts','API\CartController@validateBuyProducts');
Route::post('/applyCoupon','API\CheckOutController@applyCoupon');
Route::post('/applyCoupon2','API\CheckOutController@applyCoupon2');
Route::post('/saveOrder','API\CheckOutController@save_order');
Route::post('/saveOrdertest','API\CheckOutController2@saveOrdertest');
Route::post('/saveOrdertm','API\CheckOutController@saveOrdertm');
Route::any('/delivery_option','API\CheckOutController@delivery_option');
Route::post('/selectedPaymentGateway','API\CheckOutController@selectedPaymentGateway');

Route::any('/deliverySlots','API\CheckOutController@deliverySlots');
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
Route::get('/legalPages','API\CompareController@legalPages');
Route::get('/page/{page_url}','API\CompareController@page_url')->name('page_url');
Route::post('/coupons','API\ProductController@coupons');

Route::any('/order_track','API\CustomerController@order_track');

Route::post('/socialusersave','API\SocialController@savedata');

Route::post('/state','API\StateController@index');

Route::post('/city','API\StateController@city');
Route::post('/avl_cities','API\StateController@avl_cities');
Route::post('/city_selected','API\CartController@city_selected');
Route::any('/getNotification','API\NotificationController@getNotification');
Route::any('/test_order_url/{fld_order_id}','API\OrderController@test_order_url');

// ---------FAQ ROUTES---------
Route::any('/faqCategory','API\FAQController@faqCategory');
Route::any('/faqByCategory','API\FAQController@faqByCategory');
// ---------FAQ ROUTES---------

// ---------FCM FIREBASE ROUTES---------
Route::any('/getDeviceToken','API\CustomerController@getDeviceToken');
// ---------FCM FIREBASE ROUTES---------
