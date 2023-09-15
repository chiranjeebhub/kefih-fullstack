<?php

Route::group(['prefix' => 'admin','middleware' => ['auth:vendor' or 'auth']], function () {

//*********************************** Make **********************///

Route::get('/deliveryboy','sws_Admin\DeliveryboyController@index')->name('deliveryboy');
Route::any('/add', 'sws_Admin\DeliveryboyController@add')->name('add');
Route::any('/edit_deliveryboy/{id}','sws_Admin\DeliveryboyController@edit')->name('edit_deliveryboy');

Route::get('/deliveryboy_sts/{id}/{sts}','sws_Admin\DeliveryboyController@status')->name('deliveryboy_sts');
Route::get('/delete_deliveryboy/{id}','sws_Admin\DeliveryboyController@delete')->name('delete_deliveryboy');
Route::post('/multi_delete_deliveryboy','sws_Admin\DeliveryboyController@multi_delete')->name('multi_delete_deliveryboy');

Route::get('/exportdeliveryboy','sws_Admin\DeliveryboyController@exportdata')->name('exportdeliveryboy');
Route::post('/bulk_upload_deliveryboy','sws_Admin\DeliveryboyController@bulk_upload')->name('bulk_upload_deliveryboy');
Route::any('/filters_deliveryboy','sws_Admin\DeliveryboyController@index')->name('filters_deliveryboy');

Route::any('/assign_deliveryboy','sws_Admin\DeliveryboyController@assign')->name('assign_deliveryboy');

});

















