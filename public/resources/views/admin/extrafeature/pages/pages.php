<?php

Route::group(['prefix' => 'admin'], function () {
    
Route::get('/pages', 'sws_Admin\ExtraFunctionController@pages')->name('pages');
Route::any('/edit_page/{id}', 'sws_Admin\ExtraFunctionController@edit_page')->name('edit_page');
Route::any('/addpage', 'sws_Admin\ExtraFunctionController@add_page')->name('addpage');
Route::any('/delete_page/{id}', 'sws_Admin\ExtraFunctionController@delete_page')->name('delete_page');


});
