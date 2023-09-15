<?php

Route::group(['prefix' => 'admin'], function () {
    
    Route::get('/materirals', 'sws_Admin\AttributeController@materirals')->name('materirals');
    
    Route::get('/colors', 'sws_Admin\AttributeController@colors')->name('colors');
    
    Route::any('/addcolor', 'sws_Admin\AttributeController@addcolor')->name('addcolor');
    Route::any('/editcolor/{id}', 'sws_Admin\AttributeController@editcolor')->name('editcolor');
    Route::get('/deletecolor/{id}', 'sws_Admin\AttributeController@deletecolor')->name('deletecolor');
    Route::get('/colors_sts/{id}/{sts}', 'sws_Admin\AttributeController@color_sts')->name('colors_sts');
    Route::any('/color_search/{str}/{status}', 'sws_Admin\AttributeController@colors')->name('color_search');
    Route::any('/color_search_str/{str}', 'sws_Admin\AttributeController@colors')->name('color_search_str');
    Route::post('/multi_delete_color', 'sws_Admin\AttributeController@multi_delete_color')->name('multi_delete_color');
    
    Route::get('/sizes', 'sws_Admin\AttributeController@sizes')->name('sizes');
    Route::any('/addsize', 'sws_Admin\AttributeController@addsize')->name('addsize');
    Route::any('/editsize/{id}', 'sws_Admin\AttributeController@editsize')->name('editsize');
    Route::get('/deletesize/{id}', 'sws_Admin\AttributeController@deletesize')->name('deletesize');
    Route::get('/sizes_sts/{id}/{sts}', 'sws_Admin\AttributeController@size_sts')->name('sizes_sts');
    Route::any('/size_search/{str}/{status}', 'sws_Admin\AttributeController@sizes')->name('size_search');
    Route::any('/size_search_str/{str}', 'sws_Admin\AttributeController@sizes')->name('size_search_str');
    Route::post('/multi_delete_size', 'sws_Admin\AttributeController@multi_delete_size')->name('multi_delete_size');
    
    Route::get('/getCategoryAttributes', 'sws_Admin\AttributeController@getCategoryAttributes')->name('getCategoryAttributes');
    Route::any('/updateAttributes/{cat_id}/{type}', 'sws_Admin\AttributeController@updateAttributes')->name('updateAttributes');

});