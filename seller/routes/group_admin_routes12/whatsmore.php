<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/phaukat_offers', 'sws_Admin\WhatsmoreController@lists')->name('phaukat_offers');
Route::any('/addphaukat_offers', 'sws_Admin\WhatsmoreController@add')->name('addphaukat_offers');
Route::any('/editphaukat_offers/{id}', 'sws_Admin\WhatsmoreController@edit')->name('editphaukat_offers');
Route::get('/phaukat_offers_sts/{id}/{sts}', 'sws_Admin\WhatsmoreController@whatsmore_sts')->name('phaukat_offers_sts');
Route::get('/deletephaukat_offers/{id}', 'sws_Admin\WhatsmoreController@del')->name('deletephaukat_offers');
Route::any('/phaukat_offers_search/{str}/{status}', 'sws_Admin\WhatsmoreController@lists')->name('phaukat_offers_search');
Route::post('/multi_deletephaukat_offers', 'sws_Admin\WhatsmoreController@multi_delete_whatsmore')->name('multi_deletephaukat_offers');
});