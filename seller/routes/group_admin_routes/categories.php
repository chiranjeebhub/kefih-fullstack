<?php

Route::group(['prefix' => 'admin','middleware' => ['auth:vendor' or 'auth']], function () {
//   category route 
Route::get('/cat', 'sws_Admin\CategoryController@lists')->name('categories');
Route::any('/add_cat', 'sws_Admin\CategoryController@add')->name('add_category');
Route::any('/edit_cat/{id}', 'sws_Admin\CategoryController@edit')->name('edit_category');
Route::any('/add_sizechart/{id}', 'sws_Admin\CategoryController@add_sizechart')->name('add_sizechart');

//   category route  end 

Route::any('/cat_search/{str}/{status}', 'sws_Admin\CategoryController@lists')->name('cat_search');
Route::any('/cat_search_str/{str}', 'sws_Admin\CategoryController@lists')->name('cat_search_str');

Route::any('/cat_export', 'sws_Admin\CategoryController@cat_export')->name('cat_export');
Route::any('/cat_export_search/{str}/{status}', 'sws_Admin\CategoryController@cat_export')->name('cat_export_search');
Route::any('/cat_export_search_str/{str}', 'sws_Admin\CategoryController@cat_export')->name('cat_export_search_str');

Route::post('/multi_delete_cat', 'sws_Admin\CategoryController@multi_delete_cat')->name('multi_delete_cat');
//  common route for category 
Route::get('/cat_sts/{id}/{sts}', 'sws_Admin\CategoryController@cat_sts')->name('cat_sts');
Route::get('/delete_cat/{id}', 'sws_Admin\CategoryController@delete_category')->name('delete_category');
//  common route for category 

Route::post('/multi_vendor_delete_cat', 'sws_Admin\CategoryController@multi_vendor_delete_cat')->name('multi_vendor_delete_cat');


});



