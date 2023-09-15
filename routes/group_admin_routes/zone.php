<?php

Route::group(['prefix' => 'admin','middleware' => ['auth:vendor' or 'auth']], function () {

//*********************************** zone **********************///
Route::get('/zone', 'sws_Admin\ZoneController@index')->name('zone');
Route::any('/add_zone', 'sws_Admin\ZoneController@add')->name('add_zone');
Route::any('/edit_zone/{id}', 'sws_Admin\ZoneController@edit')->name('edit_zone');
Route::get('/zone_sts/{id}/{sts}', 'sws_Admin\ZoneController@status')->name('zone_sts');
Route::get('/delete_zone/{id}', 'sws_Admin\ZoneController@delete')->name('delete_zone');
Route::post('/multi_delete_zone', 'sws_Admin\ZoneController@multi_delete')->name('multi_delete_zone');
Route::get('/exportzone','sws_Admin\ZoneController@exportdata')->name('exportzone');
Route::post('/bulk_upload_zone', 'sws_Admin\ZoneController@bulk_upload')->name('bulk_upload_zone');
Route::any('/filters_zone', 'sws_Admin\ZoneController@searchdata')->name('filters_zone');

//*********************************** area **********************///
Route::get('/area', 'sws_Admin\AreaController@index')->name('area');
Route::any('/add_area/{ptype}', 'sws_Admin\AreaController@add')->name('add_area');
Route::any('/edit_area/{ptype}/{id}', 'sws_Admin\AreaController@edit')->name('edit_area');
Route::get('/area_sts/{id}/{sts}', 'sws_Admin\AreaController@status')->name('area_sts');
Route::get('/delete_area/{id}', 'sws_Admin\AreaController@delete')->name('delete_area');
Route::post('/multi_delete_area', 'sws_Admin\AreaController@multi_delete')->name('multi_delete_area');
Route::get('/exportarea/{ptype}','sws_Admin\AreaController@exportdata')->name('exportarea');
Route::post('/bulk_upload_area', 'sws_Admin\AreaController@bulk_upload')->name('bulk_upload_area');

});

















