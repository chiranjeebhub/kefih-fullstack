<?php

include('vendor_orders.php');

Route::group(['prefix' => 'admin','middleware' => ['auth:vendor' or 'auth']], function () {
    
Route::any('/suborder_product_details', 'sws_Admin\CommonController@suborder_product_details')->name('suborder_product_details');
Route::any('/replaceOrder', 'sws_Admin\CommonController@replaceOrder')->name('replaceOrder');
Route::get('/orders/{type}', 'sws_Admin\OrderController@index')->name('orders');

Route::get('admin_all_orders', 'sws_Admin\OrderController@admin_all_sorders')->name('admin_all_orders');
Route::any('/admin_all_sorders_filter/{str}/{daterange}/{vendor}/{brand}/{category}', 'sws_Admin\OrderController@admin_all_sorders')->name('admin_all_orders_filter');

Route::get('/order_detail/{id}', 'sws_Admin\OrderController@order_detail')->name('order_detail');
Route::get('/order_generate_invoice/{id}', 'sws_Admin\OrderController@order_generate_invoice')->name('order_generate_invoice');
Route::get('/order_invoice/{id}', 'sws_Admin\OrderController@order_invoice')->name('order_invoice');
Route::get('/order_delivered/{id}', 'sws_Admin\OrderController@order_delivered')->name('order_delivered');
Route::any('/order_shipping/{id}', 'sws_Admin\OrderController@order_shipping')->name('order_shipping');
Route::get('/order_delivered/{id}', 'sws_Admin\OrderController@order_delivered')->name('order_delivered');
Route::get('/exportorders/{type}','sws_Admin\OrderController@exportorders')->name('exportorders');
Route::get('/exportorders_with_Search/{type}/{str}/{daterange}','sws_Admin\OrderController@exportorders')->name('exportorders_with_Search');
// Route::any('/order_search/{type}/{str}/{daterange}', 'sws_Admin\OrderController@index')->name('order_search');
Route::any('/order_search/{type}/{str}/{daterange}/{vendor}', 'sws_Admin\OrderController@index')->name('order_All');
Route::any('/order_search_str/{type}/{str}', 'sws_Admin\OrderController@index')->name('order_search_str');
// Route::any('/order_search_date/{type}/{daterange}', 'sws_Admin\OrderController@index')->name('order_search_date');

Route::get('/admin_order_track/{order_detail_id}', 'sws_Admin\OrderController@admin_order_track')->name('admin_order_track'); 




Route::get('/spickupOrder/{id}', 'sws_Admin\OrderController@spickupOrder')->name('spickupOrder');
Route::get('/spackageReceived/{id}', 'sws_Admin\OrderController@spackageReceived')->name('spackageReceived');
Route::get('/srefundConfirm/{id}', 'sws_Admin\OrderController@srefundConfirm')->name('srefundConfirm');
Route::get('/sreplaceConfirm/{id}', 'sws_Admin\OrderController@sreplaceConfirm')->name('sreplaceConfirm');

Route::get('/sorder_detail/{order_detail_id}', 'sws_Admin\OrderController@suborder_detail')->name('sorder_detail');
Route::get('/sorders_view_invoice/{order_detail_id}', 'sws_Admin\OrderController@suborder_invoice')->name('sorders_view_invoice');
Route::any('/sorders_refund/{order_detail_id}', 'sws_Admin\OrderController@suborder_refund')->name('sorders_refund');
Route::any('/sorders_refund_complete/{order_detail_id}', 'sws_Admin\OrderController@sorders_refund_complete')->name('sorders_refund_complete');

Route::get('/sorders/{type}', 'sws_Admin\OrderController@sub_orders')->name('sorders');
Route::any('/sorder_search/{type}/{str}/{daterange}/{vendor}/{brand}/{category}', 'sws_Admin\OrderController@sub_orders')->name('sorder_All');

// Route::any('/sorder_search_str/{type}/{str}', 'sws_Admin\OrderController@sub_orders')->name('sorder_search_str');
// Route::any('/sorder_search_date/{type}/{daterange}', 'sws_Admin\OrderController@sub_orders')->name('sorder_search_date');
// Route::any('/sorder_search_vendor/{type}/{vendor_id}', 'sws_Admin\OrderController@sub_orders')->name('sorder_search_vendor');

Route::get('/sexportorders/{type}','sws_Admin\OrderController@sub_exportorders')->name('sexportorders');
Route::get('/sexportorders_with_Search/{type}/{str}/{daterange}/{vendor}/{brand}/{category}','sws_Admin\OrderController@sub_exportorders')->name('sexportorders_with_Search');

Route::get('admin_all_orders_export', 'sws_Admin\OrderController@admin_all_orders_export')->name('admin_all_orders_export');
Route::get('/admin_all_orders_export_filter/{str}/{daterange}/{vendor}/{brand}/{category}','sws_Admin\OrderController@admin_all_orders_export')->name('admin_all_orders_export_filter');

});
