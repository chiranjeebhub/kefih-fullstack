<?php

Route::group(['prefix' => 'admin'], function () {

Route::get('/advertise', 'sws_Admin\AdvertiseController@advertise')->name('advertise');
Route::any('/addadvertise', 'sws_Admin\AdvertiseController@addadvertise')->name('addadvertise');
Route::get('/advertise_sts/{id}/{sts}', 'sws_Admin\AdvertiseController@advertise_sts')->name('advertise_sts');
Route::get('/delete_advertise/{id}', 'sws_Admin\AdvertiseController@delete_advertise')->name('delete_advertise');
Route::any('/edit_advertise/{id}', 'sws_Admin\AdvertiseController@edit_advertise')->name('edit_advertise');
Route::any('/position_update_advertise/{id}', 'sws_Admin\AdvertiseController@position_update_advertise')->name('position_update_advertise');

});
