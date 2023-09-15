<?php

Route::group(['prefix' => 'admin'], function () {

Route::any('/vendor_pincode', 'sws_Admin\AdminController@vendor_pincode')->name('vendor_pincode');
Route::get('/vendor_pincode_sts/{id}/{sts}', 'sws_Admin\AdminController@vendor_pincode_sts')->name('vendor_pincode_sts');
Route::any('/vendor_pincode_search/{str}/{status}', 'sws_Admin\AdminController@vendor_pincode')->name('vendor_pincode_search');
Route::any('/vendor_pincode_search_str/{str}', 'sws_Admin\AdminController@vendor_pincode')->name('vendor_pincode_search_str');
Route::any('/add_vendor_pin_code', 'sws_Admin\AdminController@add_vendor_code')->name('add_vendor_code');
Route::any('/vendor_pincode_assign_csv', 'sws_Admin\AdminController@vendor_pincode_assign_csv')->name('vendor_pincode_assign_csv');
Route::any('/vendor_pincode_assign', 'sws_Admin\AdminController@vendor_pincode_assign')->name('vendor_pincode_assign');
Route::get('/delete_pincode/{id}', 'sws_Admin\AdminController@delete_pin')->name('delete_pin');
Route::any('/edit_pincode/{id}', 'sws_Admin\AdminController@edit_pin')->name('edit_pin');

});
 