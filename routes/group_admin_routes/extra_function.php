<?php

Route::group(['prefix' => 'admin'], function () {
    

Route::get('/timeslot', 'sws_Admin\TimeslotController@index')->name('timeslot');
Route::any('/addTimeslot', 'sws_Admin\TimeslotController@add')->name('addTimeslot');


Route::get('/timeslot_sts/{id}/{sts}', 'sws_Admin\TimeslotController@timeslot_sts')->name('timeslot_sts');
Route::any('/timeslotEdit/{id}', 'sws_Admin\TimeslotController@edit')->name('timeslotEdit');
Route::any('/timeslotDelete/{id}', 'sws_Admin\TimeslotController@delete')->name('timeslotDelete');

     Route::any('/admin_get_attr_dependend', 'sws_Admin\CommonController@admin_get_attr_dependend')->name('admin_get_attr_dependend');
     Route::any('/getReaplcedOrder', 'sws_Admin\CommonController@getReaplcedOrder')->name('getReaplcedOrder');
    
Route::any('/faqs', 'sws_Admin\ExtraFunctionController@faqs')->name('faqs');

Route::any('/couriercharges', 'sws_Admin\ExtraFunctionController@couriercharges')->name('couriercharges');
Route::any('/add_couriercharges', 'sws_Admin\ExtraFunctionController@add_couriercharges')->name('add_couriercharges');
Route::any('/edit_couriercharges/{couriercharges_id}', 'sws_Admin\ExtraFunctionController@edit_couriercharges')->name('edit_couriercharges');
Route::any('/delete_couriercharges/{couriercharges_id}', 'sws_Admin\ExtraFunctionController@delete_couriercharges')->name('delete_couriercharges');


Route::any('/exibition/{type}', 'sws_Admin\ExtraFunctionController@exibition')->name('exibition');
Route::any('/add_exibition', 'sws_Admin\ExtraFunctionController@add_exibition')->name('add_exibition');
Route::any('/edit_exibition/{exibition_id}', 'sws_Admin\ExtraFunctionController@edit_exibition')->name('edit_exibition');
Route::any('/delete_exibition/{exibition_id}', 'sws_Admin\ExtraFunctionController@delete_exibition')->name('delete_exibition');

Route::any('/exibitionapprove/{exibition_id}', 'sws_Admin\ExtraFunctionController@approve')->name('exibitionapprove');

Route::any('/exibitiondeapprove/{exibition_id}', 'sws_Admin\ExtraFunctionController@exibitiondeapprove')->name('exibitiondeapprove');

Route::any('/customizedProducts', 'sws_Admin\ExtraFunctionController@customizedProducts')->name('customizedProducts');
Route::any('/addcustomizedProduct', 'sws_Admin\ExtraFunctionController@addcustomizedProduct')->name('addcustomizedProduct');
Route::any('/editcustomizedProduct/{id}', 'sws_Admin\ExtraFunctionController@editcustomizedProduct')->name('edit_customized');
Route::any('/deletecustomizedProduct/{id}', 'sws_Admin\ExtraFunctionController@deletecustomizedProduct')->name('delete_customized');
Route::any('/multiDeleteCustomization', 'sws_Admin\ExtraFunctionController@multiDeleteCustomization')->name('multiDeleteCustomization');

Route::any('/newCustomization', 'sws_Admin\ExtraFunctionController@newCustomization')->name('newCustomization');
Route::any('/allCustomizationRelated/{id}', 'sws_Admin\ExtraFunctionController@allCustomizationRelated')->name('allCustomizationRelated');
Route::any('/add_faq', 'sws_Admin\ExtraFunctionController@add_faq')->name('add_faq');
Route::any('/edit_faq/{faq_id}', 'sws_Admin\ExtraFunctionController@edit_faq')->name('edit_faq');
Route::any('/delete_faq/{faq_id}', 'sws_Admin\ExtraFunctionController@delete_faq')->name('delete_faq');
Route::any('/sts_faq/{faq_id}/{sts}', 'sws_Admin\ExtraFunctionController@sts_faq')->name('sts_faq');

Route::any('/snapbooks', 'sws_Admin\ExtraFunctionController@snapbooks')->name('snapbooks');
Route::any('/remove_snapbooks/{id}', 'sws_Admin\ExtraFunctionController@remove_snapbooks')->name('remove_snapbooks');
Route::any('/shipping_charges', 'sws_Admin\ExtraFunctionController@shipping_charges')->name('shipping_charges');
    
Route::get('/offer_slider/{type}', 'sws_Admin\ExtraFunctionController@offer_slider')->name('offer_slider');

Route::get('/offers', 'sws_Admin\ExtraFunctionController@offers')->name('offerszone');
Route::get('/delete_offer/{id}', 'sws_Admin\ExtraFunctionController@delete_offer')->name('delete_offer');
Route::any('/add_offer', 'sws_Admin\ExtraFunctionController@add_offer')->name('add_offer');
Route::any('/edit_offer/{id}', 'sws_Admin\ExtraFunctionController@edit_offer')->name('edit_offer');
Route::get('/delete_offer_product/{type}/{product_id}', 'sws_Admin\ExtraFunctionController@delete_offer_product')->name('delete_offer_product');

Route::any('/save_offer_product', 'sws_Admin\ExtraFunctionController@save_offer_product')->name('save_offer_product');

Route::any('/subscribers', 'sws_Admin\ExtraFunctionController@subscriber')->name('subscribers');
Route::any('/subscriber_search/{str}/{status}', 'sws_Admin\ExtraFunctionController@subscriber')->name('subscriber_search');
Route::any('/subscriber_sts/{id}/{sts}', 'sws_Admin\ExtraFunctionController@subscriber_sts')->name('subscriber_sts');
Route::get('/deletesubscriber/{id}', 'sws_Admin\ExtraFunctionController@delete_subscriber')->name('deletesubscriber');

Route::any('/store_info', 'sws_Admin\ExtraFunctionController@store_info')->name('store_info');
Route::any('/business_info', 'sws_Admin\ExtraFunctionController@business_info')->name('business_info');
Route::any('/rating_review', 'sws_Admin\ExtraFunctionController@rating_review')->name('rating_review');

Route::get('/reports/{type}/','sws_Admin\ExtraFunctionController@reports')->name('reports');
Route::get('/reports/{type}/{daterange}','sws_Admin\ExtraFunctionController@reports')->name('report_filter');
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

});
