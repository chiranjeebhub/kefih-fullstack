<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/whatsmore', 'sws_Admin\WhatsmoreController@lists')->name('whatsmore');
Route::any('/addwhatsmore', 'sws_Admin\WhatsmoreController@add')->name('addwhatsmore');
Route::any('/editwhatsmore/{id}', 'sws_Admin\WhatsmoreController@edit')->name('editwhatsmore');
Route::get('/whatsmore_sts/{id}/{sts}', 'sws_Admin\WhatsmoreController@whatsmore_sts')->name('whatsmore_sts');
Route::get('/deletewhatsmore/{id}', 'sws_Admin\WhatsmoreController@del')->name('deletewhatsmore');
Route::any('/whatsmore_search/{str}/{status}', 'sws_Admin\WhatsmoreController@lists')->name('whatsmore_search');
Route::post('/multi_delete_whatsmore', 'sws_Admin\WhatsmoreController@multi_delete_whatsmore')->name('multi_delete_whatsmore');
});