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


});

















