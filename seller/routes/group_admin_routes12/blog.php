<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/blogs', 'sws_Admin\BlogController@lists')->name('blogs');
Route::any('/addblog', 'sws_Admin\BlogController@add')->name('addblog');
Route::any('/editblog/{id}', 'sws_Admin\BlogController@edit')->name('editblog');
Route::get('/blog_sts/{id}/{sts}', 'sws_Admin\BlogController@blog_sts')->name('blog_sts');
Route::get('/deleteblog/{id}', 'sws_Admin\BlogController@del')->name('deleteblog');
Route::any('/blog_search/{str}/{status}', 'sws_Admin\BlogController@lists')->name('blog_search');
Route::post('/multi_delete_blog', 'sws_Admin\BlogController@multi_delete_blog')->name('multi_delete_blog');
});