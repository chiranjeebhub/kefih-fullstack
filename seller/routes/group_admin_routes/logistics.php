<?php

Route::group(['prefix' => 'admin'], function () {

Route::any('/logistics', 'sws_Admin\AdminController@logistics')->name('logistics');
Route::get('/logistic_sts/{id}/{sts}', 'sws_Admin\AdminController@logistic_sts')->name('logistic_sts');
Route::any('/logistics_search/{str}/{status}', 'sws_Admin\AdminController@logistics')->name('logistics_search');
Route::any('/logistics_search_str/{str}', 'sws_Admin\AdminController@logistics')->name('logistics_search_str');
Route::any('/add_logistic', 'sws_Admin\AdminController@addlogistic')->name('addlogistic');
Route::any('/add_logistic/{level}', 'sws_Admin\AdminController@add_logistic')->name('add_logistic');
Route::any('/edit_logistic/{id}', 'sws_Admin\AdminController@edit_logistic')->name('edit_logistic');
Route::get('/delete_loggistics/{id}', 'sws_Admin\AdminController@delete_loggistics')->name('delete_loggistics');

});
