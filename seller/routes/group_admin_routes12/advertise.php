<?php

Route::group(['prefix' => 'admin'], function () {

Route::get('/advertise', 'sws_Admin\AdvertiseController@advertise')->name('advertise');
Route::any('/addadvertise', 'sws_Admin\AdvertiseController@addadvertise')->name('addadvertise');
Route::get('/advertise_sts/{id}/{sts}', 'sws_Admin\AdvertiseController@advertise_sts')->name('advertise_sts');
Route::get('/delete_advertise/{id}', 'sws_Admin\AdvertiseController@delete_advertise')->name('delete_advertise');
Route::any('/edit_advertise/{id}', 'sws_Admin\AdvertiseController@edit_advertise')->name('edit_advertise');
Route::any('/position_update_advertise/{id}', 'sws_Admin\AdvertiseController@position_update_advertise')->name('position_update_advertise');
Route::any('/position_update_mobile/{id}', 'sws_Admin\AdvertiseController@position_update_mobile')->name('position_update_mobile');

Route::get('/offers_image', 'sws_Admin\AdvertiseController@offers_image')->name('offers_image');
Route::any('/add_offers_image', 'sws_Admin\AdvertiseController@add_offers_image')->name('add_offers_image');
Route::get('/offers_image_sts/{id}/{sts}', 'sws_Admin\AdvertiseController@offers_image_sts')->name('offers_image_sts');
Route::get('/delete_offers_image_sts/{id}', 'sws_Admin\AdvertiseController@delete_offers_image_sts')->name('delete_offers_image_sts');
Route::any('/edit_offers_image/{id}', 'sws_Admin\AdvertiseController@edit_offers_image')->name('edit_offers_image');
});
