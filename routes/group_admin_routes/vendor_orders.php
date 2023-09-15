<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/vendor_orders/{type}', 'sws_Admin\VendorOrderController@index')->name('vendor_orders');

Route::get('/pickupOrder/{id}', 'sws_Admin\VendorOrderController@pickupOrderManual')->name('pickupOrder');
Route::get('/packageReceived/{id}', 'sws_Admin\VendorOrderController@packageReceived')->name('packageReceived');
Route::get('/refundConfirm/{id}', 'sws_Admin\VendorOrderController@refundConfirm')->name('refundConfirm');
Route::get('/replaceConfirm/{id}', 'sws_Admin\VendorOrderController@replaceConfirm')->name('replaceConfirm');

Route::post('/vendor_order_shipping_couirer_pickup', 'sws_Admin\VendorOrderController@vendor_order_shipping_couirer_pickup')->name('vendor_order_shipping_couirer_pickup');

Route::post('/reutrn_vendor_order_shipping_couirer_pickup', 'sws_Admin\VendorOrderController@reutrn_vendor_order_shipping_couirer_pickup')->name('reutrn_vendor_order_shipping_couirer_pickup');


// Route::any('/vendor_order_search/{type}/{str}', 'sws_Admin\VendorOrderController@vendor_order_search')->name('vendor_order_search');
Route::any('/vendor_order_search/{type}/{str}/{daterange}/{vendor}/{brand}/{category}', 'sws_Admin\VendorOrderController@index')->name('vendor_order_search_all');
Route::get('/vendor_order_detail/{order_detail_id}','sws_Admin\VendorOrderController@orderdetail')->name('vendor_order_detail');
Route::get('/vendor_order_cancel/{order_detail_id}','sws_Admin\VendorOrderController@order_cancel')->name('vendor_order_cancel');
Route::get('/vendor_order_generate_invoice/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_generate_invoice')->name('vendor_order_generate_invoice');

Route::get('/vendor_order_seller_invoice/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_seller_invoice')->name('vendor_order_seller_invoice');


Route::get('/vendor_order_completed/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_completed')->name('vendor_order_completed');
Route::get('/vendor_order_print_invoice/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_print_invoice')->name('vendor_order_print_invoice');
Route::get('/vendor_order_invoice/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_invoice')->name('vendor_order_invoice');
Route::any('/vendor_order_shipping/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_shipping_manual')->name('vendor_order_shipping');
Route::get('/vendor_order_delivered/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_delivered')->name('vendor_order_delivered');
Route::get('/vendor_order_returned/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_returned')->name('vendor_order_returned');

Route::any('/vendor_order_shipping_extradetails/{order_detail_id}', 'sws_Admin\VendorOrderController@vendor_order_shipping_extradetails')->name('vendor_order_shipping_extradetails');
Route::get('/vendor_order_sdetail/{order_detail_id}','sws_Admin\VendorOrderController@orderdetail_shipping')->name('vendor_order_sdetail');

Route::get('/exportvorders/{type}','sws_Admin\VendorOrderController@exportvorders')->name('exportvorders');
Route::get('/exportsearchorder/{type}/{str}/{daterange}/{category}/{brands}','sws_Admin\VendorOrderController@exportsearchorder')->name('exportsearchorder');


Route::get('/vendor_order_tcs_generate_invoice/{vendor_id}', 'sws_Admin\VendorOrderController@vendor_order_tcs_generate_invoice')->name('vendor_order_tcs_generate_invoice');
Route::post('/vendor_order_tcs_generate_invoice_update/{vendor_id}', 'sws_Admin\VendorOrderController@vendor_order_tcs_generate_invoice_update')->name('vendor_order_tcs_generate_invoice_update');
Route::get('/vendor_order_tcs_print_invoice/{vendor_id}/{tcs_invoice_no}', 'sws_Admin\VendorOrderController@vendor_order_tcs_print_invoice')->name('vendor_order_tcs_print_invoice');
Route::get('/vendor_order_tcs_print_vendor_invoice/{vendor_id}/{tcs_invoice_no}/{type}', 'sws_Admin\VendorOrderController@vendor_order_tcs_print_vendor_invoice')->name('vendor_order_tcs_print_vendor_invoice');

Route::get('/vendor_order_tcs_print_invoice_list/{vendor_id}', 'sws_Admin\VendorOrderController@vendor_order_tcs_invoice_list')->name('vendor_order_tcs_print_invoice_list');
Route::get('/vendor_order_tcs_invoice/{vendor_id}/{tcs_invoice_no}', 'sws_Admin\VendorOrderController@vendor_order_tcs_invoice')->name('vendor_order_tcs_invoice');



Route::post('/vendor_order_shipping_couirer_orders', 'sws_Admin\VendorOrderController@vendor_order_shipping_couirer_orders')->name('vendor_order_shipping_couirer_orders');

Route::post('/after_return_vendor_order_shipping_couirer_orders', 'sws_Admin\VendorOrderController@after_return_vendor_order_shipping_couirer_orders')->name('after_return_vendor_order_shipping_couirer_orders');

Route::post('/CourierOrderInfo', 'sws_Admin\VendorOrderController@CourierOrderInfo')->name('CourierOrderInfo');
Route::post('/afterCourierOrderInfo', 'sws_Admin\VendorOrderController@afterCourierOrderInfo')->name('afterCourierOrderInfo');

/////return process
Route::get('/pickupOrder/{id}', 'sws_Admin\VendorOrderController@pickupOrderManual')->name('pickupOrder');
Route::get('/packageReceived/{id}', 'sws_Admin\VendorOrderController@packageReceived')->name('packageReceived');
Route::get('/refundConfirm/{id}', 'sws_Admin\VendorOrderController@refundConfirm')->name('refundConfirm');
Route::get('/replaceConfirm/{id}', 'sws_Admin\VendorOrderController@replaceConfirm')->name('replaceConfirm');
/////return process


Route::get('vendor_all_orders', 'sws_Admin\VendorOrderController@vendor_all_sorders')->name('vendor_all_orders');
Route::any('/vendor_all_sorders_filter/{str}/{daterange}/{vendor}/{brand}/{category}', 'sws_Admin\VendorOrderController@vendor_all_sorders')->name('vendor_all_sorders_filter');

Route::get('vendor_all_orders_export', 'sws_Admin\VendorOrderController@vendor_all_orders_export')->name('vendor_all_orders_export');
Route::get('/vendor_all_orders_export_filter/{str}/{daterange}/{vendor}/{brand}/{category}','sws_Admin\VendorOrderController@vendor_all_orders_export')->name('vendor_all_orders_export_filter');
});
