<?php

Route::group(['prefix' => 'admin'], function () {
Route::any('/vendors', 'sws_Admin\AdminController@index')->name('vendors');
Route::any('/vendor_search/{str}/{status}', 'sws_Admin\AdminController@index')->name('vendor_search');
Route::any('/vendor_search_str/{str}', 'sws_Admin\AdminController@index')->name('vendor_search_str');
Route::any('/vendor_search_date/{daterange}', 'sws_Admin\AdminController@index')->name('vendor_search_date');
Route::any('/add_vendor/{level}', 'sws_Admin\AdminController@add_vendor')->name('add_vendor');
Route::any('/edit_vendor/{level}/{id}', 'sws_Admin\AdminController@edit_vendor')->name('edit_vendor');
Route::get('/vdr_sts/{id}/{sts}', 'sws_Admin\AdminController@vdr_sts')->name('vdr_sts');
Route::get('/vdr_verify/{id}/{type}/{sts}', 'sws_Admin\AdminController@vdr_verify')->name('vdr_verify');
Route::get('/delete_vdr/{id}', 'sws_Admin\AdminController@delete_vdr')->name('delete_vdr');
Route::post('/multi_delete_vendor', 'sws_Admin\AdminController@multi_delete_vendor')->name('multi_delete_vendor');
Route::get('/exportVendor_with_Search/{str}','sws_Admin\AdminController@exportVendor')->name('exportvendor_with_Search');
Route::get('/exportVendor','sws_Admin\AdminController@exportVendor')->name('exportVendor');

Route::get('/pdfVendor','sws_Admin\AdminController@pdfVendor')->name('pdfVendor');

Route::any('/vendor_commissons', 'sws_Admin\AdminController@vendor_commissons')->name('vendor_commissons');
Route::any('/edit_vendor_commisson/{id}', 'sws_Admin\AdminController@edit_vendor_commisson')->name('edit_vendor_commisson');
Route::any('/add_vendor_commisson', 'sws_Admin\AdminController@add_vendor_commisson')->name('add_vendor_commisson');
Route::get('/delete_vendor_commission/{id}', 'sws_Admin\AdminController@delete_vendor_commission')->name('delete_vendor_commission');
Route::any('/vendor_commission_csv', 'sws_Admin\AdminController@vendor_commission_csv')->name('vendor_commission_csv');

Route::any('/update_vdr_profile/{level}/{id}', 'sws_Admin\VendorController@update_vdr_profile')->name('update_vdr_profile');

Route::get('/vendors_order/{id}/{type}', 'sws_Admin\AdminController@vendor_orders')->name('vendors_order');
Route::get('/vendor_orders_search/{id}/{type}/{str}/{daterange}', 'sws_Admin\AdminController@vendor_orders')->name('vendor_orders_search');


Route::any('/vendor_address/{vendor_id}', 'sws_Admin\CommonController@vendor_address')->name('vendor_address');
Route::any('/add_address/{vendor_id}', 'sws_Admin\CommonController@add_address')->name('add_address');
Route::any('/edit_address/{vendor_id}/{address_id}', 'sws_Admin\CommonController@edit_address')->name('edit_address');
Route::any('/delete_address/{address_id}', 'sws_Admin\CommonController@delete_address')->name('delete_address');
}); 
