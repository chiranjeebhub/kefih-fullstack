<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/my_offers', 'sws_Admin\WhatsmoreController@lists')->name('my_offers');
Route::any('/addmy_offers', 'sws_Admin\WhatsmoreController@add')->name('addmy_offers');
Route::any('/editmy_offers/{id}', 'sws_Admin\WhatsmoreController@edit')->name('editmy_offers');
Route::get('/my_offers_sts/{id}/{sts}', 'sws_Admin\WhatsmoreController@whatsmore_sts')->name('my_offers_sts');
Route::get('/deletemy_offers/{id}', 'sws_Admin\WhatsmoreController@del')->name('deletemy_offers');
Route::any('/my_offers_search/{str}/{status}', 'sws_Admin\WhatsmoreController@lists')->name('my_offers_search');
Route::post('/multi_deletemy_offers', 'sws_Admin\WhatsmoreController@multi_delete_whatsmore')->name('multi_deletemy_offers');
});