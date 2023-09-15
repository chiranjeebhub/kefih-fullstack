<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/vendor_orders/{type}', 'sws_Admin\VendorOrderController@index')->name('vendor_orders');

Route::get('/pickupOrder/{id}', 'sws_Admin\VendorOrderController@pickupOrder')->name('pickupOrder');
Route::get('/packageReceived/{id}', 'sws_Admin\VendorOrderController@packageReceived')->name('packageReceived');

Route::post('/vendor_order_shipping_couirer_pickup', 'sws_Admin\VendorOrderController@vendor_order_shipping_couirer_pickup')->name('vendor_order_shipping_couirer_pickup');

Route::post('/reutrn_vendor_order_shipping_couirer_pickup', 'sws_Admin\VendorOrderController@reutrn_vendor_order_shipping_couirer_pickup')->name('reutrn_vendor_order_shipping_couirer_pickup');

Route::get('/refundConfirm/{id}', 'sws_Admin\VendorOrderController@refundConfirm')->name('refundConfirm');
Route::get('/replaceConfirm/{id}', 'sws_Admin\VendorOrderController@replaceConfirm')->name('replaceConfirm');
// Route::any('/vendor_order_search/{type}/{str}', 'sws_Admin\VendorOrderController@vendor_order_search')->name('vendor_order_search');
Route::any('/vendor_order_search/{type}/{str}/{daterange}/{vendor}/{brand}/{category}', 'sws_Admin\VendorOrderController@index')->name('vendor_order_search_all');
Route::get('/vendor_order_detail/{order_detail_id}','sws_Admin\VendorOrderController@orderdetail')->name('vendor_order_detail');
Route::get('/vendor_order_cancel/{order_detail_id}','sws_Admin\VendorOrderController@order_cancel')->name('vendor_order_cancel');
Route::get('/vendor_order_generate_invoice/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_generate_invoice')->name('vendor_order_generate_invoice');
Route::get('/vendor_order_print_invoice/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_print_invoice')->name('vendor_order_print_invoice');
Route::get('/vendor_order_invoice/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_invoice')->name('vendor_order_invoice');
Route::any('/vendor_order_shipping/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_shipping')->name('vendor_order_shipping');
Route::get('/vendor_order_delivered/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_delivered')->name('vendor_order_delivered');
Route::get('/vendor_order_returned/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_returned')->name('vendor_order_returned');

Route::any('/vendor_order_shipping_extradetails/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_shipping_extradetails')->name('vendor_order_shipping_extradetails');
Route::get('/vendor_order_sdetail/{order_detail_id}','sws_Admin\VendorOrderController@orderdetail_shipping')->name('vendor_order_sdetail');

Route::get('/exportvorders/{type}','sws_Admin\VendorOrderController@exportvorders')->name('exportvorders');
Route::get('/exportsearchorder/{type}/{str}/{daterange}/{category}/{brands}','sws_Admin\VendorOrderController@exportsearchorder')->name('exportsearchorder');





Route::post('/vendor_order_shipping_couirer_orders', 'sws_Admin\VendorOrderController@vendor_order_shipping_couirer_orders')->name('vendor_order_shipping_couirer_orders');

Route::post('/after_return_vendor_order_shipping_couirer_orders', 'sws_Admin\VendorOrderController@after_return_vendor_order_shipping_couirer_orders')->name('after_return_vendor_order_shipping_couirer_orders');

Route::post('/CourierOrderInfo', 'sws_Admin\VendorOrderController@CourierOrderInfo')->name('CourierOrderInfo');
Route::post('/afterCourierOrderInfo', 'sws_Admin\VendorOrderController@afterCourierOrderInfo')->name('afterCourierOrderInfo');

});
