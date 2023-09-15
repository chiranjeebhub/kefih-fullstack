<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/brands', 'sws_Admin\BrandController@lists')->name('brands');
Route::any('/addbrand', 'sws_Admin\BrandController@add')->name('addbrand');
Route::any('/editbrand/{id}', 'sws_Admin\BrandController@edit')->name('editbrand');
Route::get('/deletebrand/{id}', 'sws_Admin\BrandController@del')->name('deletebrand');
Route::get('/brands_sts/{id}/{sts}', 'sws_Admin\BrandController@brand_sts')->name('brands_sts');
Route::any('/brand_search/{str}/{status}', 'sws_Admin\BrandController@lists')->name('brand_search');
Route::any('/brand_search_str/{str}', 'sws_Admin\BrandController@lists')->name('brand_search_str');
Route::post('/multi_delete_brand', 'sws_Admin\BrandController@multi_delete_brand')->name('multi_delete_brand');
});