<?php
use Illuminate\Http\Request;
///file_put_contents(time()."req.txt",json_encode($_SERVER));
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

Route::middleware('auth:deliveryboy')->get('/user', function (Request $request) {
    return $request->user();
});
   

Route::post('/login','API\DeliveryboyController@login');

Route::post('/verifyOtp','API\DeliveryboyController@verifyOtp');
Route::post('/resend_otp','API\DeliveryboyController@resend_otp');
Route::post('/forget','API\DeliveryboyController@forget');
Route::post('/update_password','API\DeliveryboyController@update_password');
Route::post('/orderlist','API\DeliveryboyController@orderlist');
Route::post('/orderDetails','API\DeliveryboyController@orderDetails');
Route::post('/changepassword','API\DeliveryboyController@changepassword');
Route::post('/updateOrderImageUpload','API\DeliveryboyController@updateOrderImageUpload');
Route::post('/updateOrder_status','API\DeliveryboyController@updateOrder_status');
Route::post('/resend_otp','API\DeliveryboyController@resend_otp');
Route::post('/address','API\DeliveryboyController@address');
Route::post('/getReason','API\DeliveryboyController@getReason');
Route::post('/profile','API\DeliveryboyController@profile');
Route::post('/updateProfile','API\DeliveryboyController@updateProfile');
Route::post('/ordersCount','API\DeliveryboyController@ordersCount');