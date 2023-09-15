<?php

Route::group(['prefix' => 'admin'], function () {
//   category route 
Route::get('/cat', 'sws_Admin\CategoryController@lists')->name('categories');
Route::any('/add_cat', 'sws_Admin\CategoryController@add')->name('add_category');
Route::any('/edit_cat/{id}', 'sws_Admin\CategoryController@edit')->name('edit_category');
Route::get('/category_export', 'sws_Admin\CategoryController@category_export')->name('category_export');
Route::any('/categorywisesizechart/{category}', 'sws_Admin\CategoryController@categorywisesizechart')->name('categorywisesizechart');

//   category route  end 

Route::any('/cat_search/{str}/{status}', 'sws_Admin\CategoryController@lists')->name('cat_search');
Route::any('/cat_search_str/{str}', 'sws_Admin\CategoryController@lists')->name('cat_search_str');
Route::post('/multi_delete_cat', 'sws_Admin\CategoryController@multi_delete_cat')->name('multi_delete_cat');
//  common route for category 
Route::get('/cat_sts/{id}/{sts}', 'sws_Admin\CategoryController@cat_sts')->name('cat_sts');
Route::get('/delete_cat/{id}', 'sws_Admin\CategoryController@delete_category')->name('delete_category');
//  common route for category 

//size chart start
Route::get('/admincat/{id}', 'sws_Admin\CategorysizechartController@adminlists')->name('admincategories');
Route::any('/adminadd_cat', 'sws_Admin\CategorysizechartController@adminadd')->name('adminadd_category');
Route::any('/adminedit_cat/{id}', 'sws_Admin\CategorysizechartController@adminedit')->name('adminedit_category');
Route::any('/adminadd_sizechart/{id}/{vendor}', 'sws_Admin\CategorysizechartController@adminadd_sizechart')->name('adminadd_sizechart');

//   category route  end 

Route::any('/admincat_search/{str}/{status}', 'sws_Admin\CategorysizechartController@adminlists')->name('admincat_search');
Route::any('/admincat_search_str/{str}', 'sws_Admin\CategorysizechartController@adminlists')->name('admincat_search_str');

Route::any('/admincat_export', 'sws_Admin\CategorysizechartController@admincat_export')->name('admincat_export');
Route::any('/admincat_export_search/{str}/{status}', 'sws_Admin\CategorysizechartController@admincat_export')->name('admincat_export_search');
Route::any('/admincat_export_search_str/{str}', 'sws_Admin\CategorysizechartController@admincat_export')->name('admincat_export_search_str');

Route::post('/adminmulti_delete_cat', 'sws_Admin\CategorysizechartController@adminmulti_delete_cat')->name('adminmulti_delete_cat');
Route::post('/multi_vendor_delete_cat', 'sws_Admin\CategorysizechartController@multi_vendor_delete_cat')->name('multi_vendor_delete_cat');


//  common route for category 
Route::get('/admincat_sts/{id}/{sts}', 'sws_Admin\CategorysizechartController@admincat_sts')->name('admincat_sts');
Route::get('/admindelete_cat/{id}', 'sws_Admin\CategorysizechartController@admindelete_category')->name('admindelete_category');
//  common route for category 

});



