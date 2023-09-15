<?php

Route::group(['prefix' => 'admin'], function () {
    
Route::get('/city', 'sws_Admin\CityController@lists')->name('city');
Route::any('/addcity', 'sws_Admin\CityController@add')->name('addcity');
Route::any('/editcity/{id}', 'sws_Admin\CityController@edit')->name('editcity');
Route::get('/city_sts/{id}/{sts}', 'sws_Admin\CityController@city_sts')->name('city_sts');

Route::get('/deletecity/{id}', 'sws_Admin\CityController@del')->name('deletecity');
Route::post('/multi_delete_city', 'sws_Admin\CityController@multi_delete_city')->name('multi_delete_city');
Route::any('/city_search_str/{str}', 'sws_Admin\CityController@lists')->name('city_search_str');

});
