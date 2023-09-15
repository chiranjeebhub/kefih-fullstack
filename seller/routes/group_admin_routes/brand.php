<?php

Route::group(['prefix' => 'admin','middleware' => ['auth:vendor' or 'auth']], function () {
Route::get('/brands', 'sws_Admin\BrandController@lists')->name('brands');

Route::any('/addbrand', 'sws_Admin\BrandController@add')->name('addbrand');
Route::any('/editbrand/{id}', 'sws_Admin\BrandController@edit')->name('editbrand');
Route::get('/deletebrand/{id}', 'sws_Admin\BrandController@del')->name('deletebrand');
Route::get('/brands_sts/{id}/{sts}', 'sws_Admin\BrandController@brand_sts')->name('brands_sts');
Route::any('/brand_search/{str}/{status}', 'sws_Admin\BrandController@lists')->name('brand_search');

Route::any('/brand_export', 'sws_Admin\BrandController@brand_export')->name('brand_export');

Route::any('/brand_search_str/{str}', 'sws_Admin\BrandController@lists')->name('brand_search_str');
Route::post('/multi_delete_brand', 'sws_Admin\BrandController@multi_delete_brand')->name('multi_delete_brand');


Route::get('/vendor_brands', 'sws_Admin\VendorController@lists')->name('vendor_brands');
Route::any('/vendor_addbrand', 'sws_Admin\VendorController@vendor_addbrand')->name('vendor_addbrand');
Route::any('/vendor_editbrand/{id}', 'sws_Admin\VendorController@vendor_editbrand')->name('vendor_editbrand');
Route::any('/vendor_brand_search/{str}/{status}', 'sws_Admin\VendorController@lists')->name('vendor_brand_search');
Route::any('/vendor_brand_export', 'sws_Admin\VendorController@brand_export')->name('vendor_brand_export');
Route::any('/vendor_brand_search_str/{str}', 'sws_Admin\VendorController@lists')->name('vendor_brand_search_str');
Route::get('/vendor_deletebrand/{id}', 'sws_Admin\VendorController@vendor_deletebrand')->name('vendor_deletebrand');

});