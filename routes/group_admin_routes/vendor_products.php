<?php

Route::group(['prefix' => 'admin'], function () {
Route::any('/vendor_home', 'sws_Admin\VendorController@gotHome')->name('vendor_home');
Route::any('/v_home', 'sws_Admin\VendorController@gotHome')->name('v_home');
Route::get('/vendor_product', 'sws_Admin\VendorController@index')->name('vendor_product');



Route::any('/vendor_filters_products/{vendor}/{sts}/{str}/{type}/{category_id}/{brands}/{blocked}', 'sws_Admin\VendorController@vendor_filters_products')->name('vendor_filters_products');

Route::any('/addvendorproduct', 'sws_Admin\VendorController@add')->name('addvendorproduct');
Route::any('/editvendorproduct/{id}', 'sws_Admin\VendorController@edit')->name('editvendorproduct');
Route::get('/deletevendorproduct/{id}', 'sws_Admin\VendorController@del')->name('deletevendorproduct');
Route::get('/search','sws_Admin\VendorController@search')->name('search');
Route::get('/getProDetails','sws_Admin\VendorController@getProDetails')->name('getProDetails');
Route::post('/addSellProduct','sws_Admin\VendorController@addSellProduct')->name('addSellProduct');
Route::any('/existing_product', 'sws_Admin\VendorController@existing_product')->name('existing_product');

Route::any('/add_vdr_product/{level}', 'sws_Admin\VendorController@add')->name('add_product_vdr');
Route::any('/edit_vdr_product/{level}/{id}', 'sws_Admin\VendorController@edit_product')->name('edit_product_vdr');

});
