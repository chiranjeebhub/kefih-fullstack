<?php

Route::group(['prefix' => 'admin','middleware' => ['auth:vendor' or 'auth']], function () {
Route::get('/filters', 'sws_Admin\FiltersController@lists')->name('filters');
Route::any('/addfilter', 'sws_Admin\FiltersController@add')->name('addfilter');
Route::any('/editfilter/{id}', 'sws_Admin\FiltersController@edit')->name('editfilter');
Route::get('/filters_sts/{id}/{sts}', 'sws_Admin\FiltersController@filter_sts')->name('filters_sts');

Route::any('/update_filter_value/{id}', 'sws_Admin\FiltersController@update_filter_value')->name('update_filter_value');
Route::any('/assign_cat_to_filter/{id}', 'sws_Admin\FiltersController@assign_cat_to_filter')->name('assign_cat_to_filter');
Route::get('/deletefilter/{id}', 'sws_Admin\FiltersController@del')->name('deletefilter');
Route::get('/deletefilterValue/{id}', 'sws_Admin\FiltersController@deletefilterValue')->name('deletefilterValue');
Route::any('/filterValue', 'sws_Admin\FiltersController@filterValue')->name('filterValue');
Route::any('/filter_search/{str}/{status}', 'sws_Admin\FiltersController@lists')->name('filter_search');
Route::any('/filter_search_str/{str}', 'sws_Admin\FiltersController@lists')->name('filter_search_str');
Route::post('/multi_delete_filter', 'sws_Admin\FiltersController@multi_delete_filter')->name('multi_delete_filter');
});