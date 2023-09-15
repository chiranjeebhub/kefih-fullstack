<?php

Route::group(['prefix' => 'admin','middleware' => ['auth:vendor' or 'auth']], function () {
Route::get('/ledger', 'sws_Admin\LedgerController@index')->name('ledger');

Route::get('/ledger_export', 'sws_Admin\LedgerController@ledger_export')->name('ledger_export');

Route::any('/ledger_search/{str}/{daterange}', 'sws_Admin\LedgerController@index')->name('ledger_search');
Route::any('/filter_ledger_export/{str}/{daterange}', 'sws_Admin\LedgerController@ledger_export')->name('filter_ledger_export');

Route::get('/ledger_detail/{vendor_id}', 'sws_Admin\LedgerController@ledger_detail')->name('ledger_detail');
Route::get('/ledger_detail_serach/{vendor_id}/{date_range}', 'sws_Admin\LedgerController@ledger_detail')->name('ledger_detail_sera');
Route::get('/ledger_vendor_export/{vendor_id}', 'sws_Admin\LedgerController@ledger_vendor_export')->name('ledger_vendor_export');
Route::get('/ledger_vendor_export_search/{vendor_id}/{date_range}', 'sws_Admin\LedgerController@ledger_vendor_export')->name('ledger_vendor_export_search');

Route::any('/ledger_detail_search/{daterange}', 'sws_Admin\LedgerController@ledger_detail')->name('ledger_detail_search');
Route::any('/ledger_detail_search_date/{vendor_id}/{daterange}', 'sws_Admin\LedgerController@ledger_detail')->name('ledger_detail_search_date');

Route::any('/vendor_pay/{vendor_id}', 'sws_Admin\LedgerController@vendor_pay')->name('vendor_pay');

Route::get('/vendor_payment/{vendor_id}', 'sws_Admin\LedgerController@vendor_payment_history')->name('vendor_payment');
Route::any('/vendor_payment_search/{vendor_id}/{str}/{daterange}', 'sws_Admin\LedgerController@vendor_payment_history')->name('vendor_payment_search');

Route::any('/vendor_payment_export/{vendor_id}', 'sws_Admin\LedgerController@vendor_payment_export')->name('vendor_payment_export');
Route::any('/vendor_payment_search_export/{vendor_id}/{str}/{daterange}', 'sws_Admin\LedgerController@vendor_payment_export')->name('vendor_payment_search_export');


Route::get('/vledger', 'sws_Admin\VendorLedgerController@index')->name('vledger');
Route::get('/vledger_detail/{vendor_id}', 'sws_Admin\VendorLedgerController@ledger_detail')->name('vledger_detail');
Route::get('/vvendor_payment/{vendor_id}', 'sws_Admin\VendorLedgerController@vendor_payment_history')->name('vvendor_payment');

});