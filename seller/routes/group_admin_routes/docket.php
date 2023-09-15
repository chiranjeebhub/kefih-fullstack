<?php

Route::group(['prefix' => 'admin','middleware' => ['auth:vendor' or 'auth']], function () {

Route::get('/docket', 'sws_Admin\DocketController@docket')->name('docket');
Route::any('/adddocket', 'sws_Admin\DocketController@adddocket')->name('adddocket');
Route::any('/filters_docket/{sts}/{dtsts}/{docket_no}', 'sws_Admin\DocketController@filters_docket')->name('filters_docket');

Route::any('/exportDocket', 'sws_Admin\DocketController@exportDocket')->name('exportDocket');
Route::any('/exportDocket_with_Search/{sts}/{dtsts}/{docket_no}', 'sws_Admin\DocketController@exportDocket_with_Search')->name('exportDocket_with_Search');

});
