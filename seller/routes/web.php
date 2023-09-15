<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['auth:vendor' or 'auth']], function () {
$real_path = realpath(__DIR__).DIRECTORY_SEPARATOR.'group_admin_routes'.DIRECTORY_SEPARATOR;
include($real_path .'categories.php');
include($real_path .'brand.php');
include($real_path .'materials.php');
include($real_path .'filters.php');
include($real_path .'attributes.php');
include($real_path .'products.php');
include($real_path .'coupon.php');
include($real_path .'vendor_products.php');
include($real_path .'customers.php');
include($real_path .'vendors.php');
include($real_path .'logistics.php');
include($real_path .'pincode.php');
include($real_path .'orders.php');
include($real_path .'permissions.php');
include($real_path .'users_role.php');
include($real_path .'extra_function.php');
include($real_path .'notification.php');
include($real_path .'banner.php');
include($real_path .'advertise.php');
include($real_path .'blog.php');
include($real_path .'whatsmore.php');
include($real_path .'testimonial.php');
include($real_path .'ledger.php');
include($real_path .'pages.php');
});



Route::group(['prefix' => ''], function () {
    

Route::any('/', 'sws_Vendor\UserController@vendor_login')->name('sellerLogin');
Route::any('/sellerLogin', 'sws_Vendor\UserController@vendor_login')->name('sellerLogin');

Route::get('/page/{page_url}','PagesController@page_url')->name('page_url');


Route::any('/login', 'sws_Vendor\UserController@vendor_login')->name('admin_login');
Route::get('/vendor_logout', 'sws_Vendor\UserController@vendor_logout')->name('vendor_logout');
Route::get('/admin_logout', 'sws_Vendor\UserController@admin_logout')->name('admin_logout');


Route::any('/vendor_register/{level}', 'sws_Vendor\UserController@vendor_register')->name('vendor_register');
Route::get('/email_verify/{email}/{code}', 'sws_Vendor\UserController@email_verify')->name('email_verify');
Route::any('/vendor_login', 'sws_Vendor\UserController@vendor_login')->name('vendor_login');

Route::any('/resend_otp', 'sws_Vendor\UserController@resend_otp')->name('resend_otp');


	Route::post('/filterCityOnState','HomeController@filterCityOnState')->name('filterCityOnState');
Route::any('/vendor_forgot_password', 'sws_Vendor\UserController@vendor_forgot_password')->name('vendor_forgot_password');  
Route::any('/update_password', 'sws_Vendor\UserController@vendor_update_password')->name('vendor_update_password');
Route::any('/verify_phone', 'sws_Vendor\UserController@verify_phone')->name('verify_phone');
Route::any('/vendor_resend_otp', 'sws_Vendor\UserController@vendor_resend_otp')->name('vendor_resend_otp');
Route::any('/vendor_phone_resend_otp', 'sws_Vendor\UserController@vendor_phone_resend_otp')->name('vendor_phone_resend_otp');

Route::any('/cronstock', 'sws_Vendor\UserController@cron_track')->name('cron_track');



Route::get('/vendors-enquiry', 'sws_Admin\ProductController@enquirry_vendor')->name('vendors_enquiry');
});







Auth::routes();



Route::group(['middleware' => ['web']], function () {

	
});






