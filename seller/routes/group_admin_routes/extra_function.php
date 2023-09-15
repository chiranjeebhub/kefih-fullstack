<?php

Route::group(['prefix' => 'admin','middleware' => ['auth:vendor' or 'auth']], function () {
    
     Route::any('/admin_get_attr_dependend', 'sws_Admin\CommonController@admin_get_attr_dependend')->name('admin_get_attr_dependend');
     Route::any('/getReaplcedOrder', 'sws_Admin\CommonController@getReaplcedOrder')->name('getReaplcedOrder');
    
Route::any('/faqs', 'sws_Admin\ExtraFunctionController@faqs')->name('faqs');
Route::any('/add_faq', 'sws_Admin\ExtraFunctionController@add_faq')->name('add_faq');
Route::any('/edit_faq/{faq_id}', 'sws_Admin\ExtraFunctionController@edit_faq')->name('edit_faq');
Route::any('/delete_faq/{faq_id}', 'sws_Admin\ExtraFunctionController@delete_faq')->name('delete_faq');
Route::any('/sts_faq/{faq_id}/{sts}', 'sws_Admin\ExtraFunctionController@sts_faq')->name('sts_faq');


Route::any('/shipping_charges', 'sws_Admin\ExtraFunctionController@shipping_charges')->name('shipping_charges');
    
Route::get('/offer_slider/{type}', 'sws_Admin\ExtraFunctionController@offer_slider')->name('offer_slider');

Route::get('/offers', 'sws_Admin\ExtraFunctionController@offers')->name('offers');
Route::get('/delete_offer/{id}', 'sws_Admin\ExtraFunctionController@delete_offer')->name('delete_offer');
Route::any('/add_offer', 'sws_Admin\ExtraFunctionController@add_offer')->name('add_offer');
Route::any('/edit_offer/{id}', 'sws_Admin\ExtraFunctionController@edit_offer')->name('edit_offer');
Route::get('/delete_offer_product/{type}/{product_id}', 'sws_Admin\ExtraFunctionController@delete_offer_product')->name('delete_offer_product');

Route::any('/save_offer_product', 'sws_Admin\ExtraFunctionController@save_offer_product')->name('save_offer_product');

Route::any('/subscribers', 'sws_Admin\ExtraFunctionController@subscriber')->name('subscribers');
Route::any('/subscriber_search/{str}/{status}', 'sws_Admin\ExtraFunctionController@subscriber')->name('subscriber_search');

Route::any('/subscriber_export', 'sws_Admin\ExtraFunctionController@subscriber_export')->name('subscriber_export');
Route::any('/subscriber_export/{str}/{status}', 'sws_Admin\ExtraFunctionController@subscriber_export')->name('subscriber_export_search');

Route::any('/subscriber_sts/{id}/{sts}', 'sws_Admin\ExtraFunctionController@subscriber_sts')->name('subscriber_sts');
Route::get('/deletesubscriber/{id}', 'sws_Admin\ExtraFunctionController@delete_subscriber')->name('deletesubscriber');

Route::any('/store_info', 'sws_Admin\ExtraFunctionController@store_info')->name('store_info');

Route::any('/rating_review', 'sws_Admin\ExtraFunctionController@rating_review')->name('rating_review');

Route::get('/reports/{type}/','sws_Admin\ExtraFunctionController@reports')->name('reports');
Route::get('/reports/{type}/{daterange}/{cat}','sws_Admin\ExtraFunctionController@reports')->name('report_filter');

Route::get('/ex_reports/{type}','sws_Admin\ExtraFunctionController@ex_reports')->name('export_report');
Route::get('/ex_reports/{type}/{daterange}/{cat}','sws_Admin\ExtraFunctionController@ex_reports')->name('export_report_filter');

Route::any('/selectedReview', 'sws_Admin\ExtraFunctionController@selectedReview')->name('selectedReview');
Route::any('/edit_rating_review/{product_id}/{rating_id}', 'sws_Admin\ExtraFunctionController@edit_rating_review')->name('edit_rating_review');

Route::any('/reasons', 'sws_Admin\ExtraFunctionController@reasons')->name('reasons');
Route::any('/reasons/{reasons_type}/{status}', 'sws_Admin\ExtraFunctionController@reasons')->name('reasons_type');
Route::get('/reasons_sts/{id}/{sts}', 'sws_Admin\ExtraFunctionController@reasons_sts')->name('reasons_sts');
Route::any('/add_reason', 'sws_Admin\ExtraFunctionController@add_reason')->name('add_reason');
Route::get('/delete_reason/{id}', 'sws_Admin\ExtraFunctionController@delete_reason')->name('delete_reason');
Route::any('/edit_reason/{id}', 'sws_Admin\ExtraFunctionController@edit_reason')->name('edit_reason');

Route::any('/changePassword', 'sws_Admin\CommonController@changePassword')->name('changePassword');
Route::any('/chat', 'sws_Admin\ChatController@index'); 
Route::any('/send', 'sws_Admin\ChatController@postSendMessage'); 
Route::get('/load-latest-messages', 'sws_Admin\ChatController@getLoadLatestMessages');
Route::get('/fetch-old-messages', 'sws_Admin\ChatController@getOldMessages');

});
