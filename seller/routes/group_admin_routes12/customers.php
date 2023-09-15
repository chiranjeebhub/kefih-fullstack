<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/customers', 'sws_Admin\CommonController@customers')->name('customers');
Route::get('/customers_pay', 'sws_Admin\CommonController@customers_payment')->name('customers_pay');
Route::get('/customers_export', 'sws_Admin\CommonController@customers_export')->name('customers_export');
Route::any('/addCustomer', 'sws_Admin\CommonController@addCustomer')->name('addCustomer');

Route::get('/verify_customer_phone/{id}/{sts}', 'sws_Admin\CommonController@verify_customer_phone')->name('verify_customer_phone');
Route::get('/deletecustomer/{id}', 'sws_Admin\CommonController@del')->name('deletecustomer');
Route::any('/editcustomer/{id}', 'sws_Admin\CommonController@editcustomer')->name('editcustomer');
Route::get('/customer_detail/{id}', 'sws_Admin\CommonController@customer_detail')->name('customer_detail');
Route::get('/customer_sts/{id}/{sts}', 'sws_Admin\CommonController@customer_sts')->name('customer_sts');
Route::get('/customer_wallet/{id}', 'sws_Admin\CommonController@customer_wallet')->name('customer_wallet');
Route::get('/customer_referals/{id}', 'sws_Admin\CommonController@customer_referals')->name('customer_referals');
Route::get('/customers_search/{name}/{status}/{phonestatus}', 'sws_Admin\CommonController@customers')->name('customers_search');
Route::get('/customers_search_export/{name}/{status}/{phonestatus}', 'sws_Admin\CommonController@customers_export')->name('customers_search_export');
// Route::get('/customers_search_str/{name}', 'sws_Admin\CommonController@customers')->name('customers_search_str');
//Route::get('/customers_search_date/{daterange}', 'sws_Admin\CommonController@customers')->name('customers_search_date');

Route::get('/customer_orders/{id}/{type}', 'sws_Admin\CommonController@customer_orders')->name('customer_orders');
Route::get('/customer_orders_search/{id}/{type}/{str}/{daterange}', 'sws_Admin\CommonController@customer_orders')->name('customer_orders_search');
});