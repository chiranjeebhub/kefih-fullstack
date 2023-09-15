<?php

Route::group(['prefix' => 'admin'], function () {

    
    
    Route::get('/vendor_brands', 'sws_Admin\VendorController@lists')->name('vendor_brands');
    Route::any('/vendor_addbrand', 'sws_Admin\VendorController@vendor_addbrand')->name('vendor_addbrand');
    Route::any('/vendor_editbrand/{id}', 'sws_Admin\VendorController@vendor_editbrand')->name('vendor_editbrand');
    Route::any('/vendor_brand_search/{str}/{status}', 'sws_Admin\VendorController@lists')->name('vendor_brand_search');
    Route::any('/vendor_brand_export', 'sws_Admin\VendorController@brand_export')->name('vendor_brand_export');
    Route::any('/vendor_brand_search_str/{str}', 'sws_Admin\VendorController@lists')->name('vendor_brand_search_str');
    Route::get('/vendor_deletebrand/{id}', 'sws_Admin\VendorController@vendor_deletebrand')->name('vendor_deletebrand');


});