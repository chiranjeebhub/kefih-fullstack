<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/ledger', 'sws_Admin\LedgerController@index')->name('ledger');
Route::any('/ledger_search/{str}/{daterange}', 'sws_Admin\LedgerController@index')->name('ledger_search');
Route::any('/ledger_search_str/{str}', 'sws_Admin\LedgerController@index')->name('ledger_search_str');
Route::any('/ledger_search_date/{daterange}', 'sws_Admin\LedgerController@index')->name('ledger_search_date');
Route::get('/ledger_detail/{vendor_id}', 'sws_Admin\LedgerController@ledger_detail')->name('ledger_detail');
Route::any('/ledger_detail_search/{daterange}', 'sws_Admin\LedgerController@ledger_detail')->name('ledger_detail_search');
Route::any('/ledger_detail_search_date/{vendor_id}/{daterange}', 'sws_Admin\LedgerController@ledger_detail')->name('ledger_detail_search_date');

Route::any('/vendor_pay/{vendor_id}', 'sws_Admin\LedgerController@vendor_pay')->name('vendor_pay');
Route::get('/vendor_payment/{vendor_id}', 'sws_Admin\LedgerController@vendor_payment_history')->name('vendor_payment');
Route::any('/vendor_payment_search/{vendor_id}/{str}/{daterange}', 'sws_Admin\LedgerController@vendor_payment_history')->name('vendor_payment_search');
Route::any('/vendor_payment_search_str/{vendor_id}/{str}', 'sws_Admin\LedgerController@vendor_payment_history')->name('vendor_payment_search_str');
Route::any('/vendor_payment_search_date/{vendor_id}/{daterange}', 'sws_Admin\LedgerController@vendor_payment_history')->name('vendor_payment_search_date');

});