<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/testimonials', 'sws_Admin\TestimonialController@lists')->name('testimonials');
Route::any('/addtestimonial', 'sws_Admin\TestimonialController@add')->name('addtestimonial');
Route::any('/edittestimonial/{id}', 'sws_Admin\TestimonialController@edit')->name('edittestimonial');
Route::get('/deletetestimonial/{id}', 'sws_Admin\TestimonialController@del')->name('deletetestimonial');
Route::get('/testimonial_sts/{id}/{sts}', 'sws_Admin\TestimonialController@testimonial_sts')->name('testimonial_sts');
Route::any('/testimonial_search/{str}/{status}', 'sws_Admin\TestimonialController@lists')->name('testimonial_search');
Route::post('/multi_delete_testimonial', 'sws_Admin\TestimonialController@multi_delete_testimonial')->name('multi_delete_testimonial');
});