<?php

Route::group(['prefix' => 'admin'], function () {

Route::get('/home_slider', 'sws_Admin\ExtraFunctionController@home_slider')->name('home_slider');
Route::any('/banner_search', 'sws_Admin\ExtraFunctionController@home_slider')->name('banner_search');
Route::any('/addbanner', 'sws_Admin\ExtraFunctionController@addbanner')->name('addbanner');
Route::get('/banner_sts/{id}/{sts}', 'sws_Admin\ExtraFunctionController@banner_sts')->name('banner_sts');
Route::get('/delete_banner/{id}', 'sws_Admin\ExtraFunctionController@delete_banner')->name('delete_banner');
Route::any('/edit_banner/{id}', 'sws_Admin\ExtraFunctionController@edit_banner')->name('edit_banner');

});
