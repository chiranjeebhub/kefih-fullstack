<?php

Route::group(['prefix' => 'admin'], function () {
//   category route 
Route::get('/coupons/{type}', 'sws_Admin\CouponController@lists')->name('coupons');
Route::any('/addCoupon', 'sws_Admin\CouponController@add')->name('addCoupon');
Route::any('/couponDetail/{id}', 'sws_Admin\CouponController@couponDetail')->name('couponDetail');
Route::any('/couponaAssign/{id}', 'sws_Admin\CouponController@couponaAssign')->name('couponaAssign');
  Route::get('/get-product-name', 'sws_Admin\CouponController@getProductName')->name('getProductName');
Route::get('/coupon_sts/{id}/{sts}', 'sws_Admin\CouponController@coupon_sts')->name('coupon_sts');
Route::get('/coupons_export', 'sws_Admin\CouponController@coupons_export')->name('coupons_export');

Route::any('/couponEdit/{id}', 'sws_Admin\CouponController@couponEdit')->name('couponEdit');
Route::any('/couponDelete/{node}/{id}', 'sws_Admin\CouponController@couponDelete')->name('couponDelete');
});
