<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/products', 'sws_Admin\ProductController@index')->name('products');

Route::any('/up_sell_product_search', 'sws_Admin\CommonController@up_sell_product_search')->name('up_sell_product_search');
Route::any('/cross_sell_product_search', 'sws_Admin\CommonController@cross_sell_product_search')->name('cross_sell_product_search');
Route::any('/related_products_search', 'sws_Admin\CommonController@related_products_search')->name('related_products_search');
Route::any('/prd_review/{id}', 'sws_Admin\CommonController@product_review')->name('preview');



Route::any('/qa/{id}', 'sws_Admin\ProductController@qa')->name('qa');
Route::any('/imageUploads', 'sws_Admin\ProductController@imageUploads')->name('imageUploads');
Route::any('/edit_qa/{id}', 'sws_Admin\ProductController@edit_qa')->name('edit_qa');

Route::any('/product_description/{id}', 'sws_Admin\ProductController@product_description')->name('product_description');
Route::any('/add_description/{id}', 'sws_Admin\ProductController@add_description')->name('add_description');
Route::any('/edit_description/{id}/{description_id}', 'sws_Admin\ProductController@edit_description')->name('edit_description');
Route::any('/delete_description/{id}', 'sws_Admin\ProductController@delete_description')->name('delete_description');



Route::any('/product_general/{id}', 'sws_Admin\ProductController@product_general')->name('product_general');
Route::any('/add_general/{id}', 'sws_Admin\ProductController@add_general')->name('add_general');
Route::any('/edit_general/{id}/{general_id}', 'sws_Admin\ProductController@edit_general')->name('edit_general');
Route::any('/delete_general/{id}', 'sws_Admin\ProductController@delete_general')->name('delete_general');


Route::any('/add_qa/{id}', 'sws_Admin\ProductController@add_qa')->name('add_qa');
Route::any('/delete_qa/{id}', 'sws_Admin\ProductController@delete_qa')->name('delete_qa');
Route::any('/qa_sts/{id}/{sts}', 'sws_Admin\ProductController@qa_sts')->name('qa_sts');

Route::any('/add_product/{level}', 'sws_Admin\ProductController@add')->name('add_product');
Route::any('/edit_product/{level}/{id}', 'sws_Admin\ProductController@edit_product')->name('edit_product');
Route::get('/prd_sts/{id}/{sts}', 'sws_Admin\ProductController@prd_sts')->name('prd_sts');
Route::get('/delete_prd/{id}', 'sws_Admin\ProductController@delete_prd')->name('delete_prd');

Route::any('/addStock/{id}', 'sws_Admin\ProductController@addStock')->name('addStock');
Route::get('/exportProduct','sws_Admin\ProductController@exportProduct')->name('exportProduct');

Route::any('/productSetting','sws_Admin\ProductController@productSetting')->name('productSetting');
Route::any('/bulkActiveProduct','sws_Admin\ProductController@bulkActiveProduct')->name('bulkActiveProduct');

Route::get('/exportProduct_with_Search/{str}','sws_Admin\ProductController@exportProduct')->name('exportProduct_with_Search');
Route::any('/product_search/{str}', 'sws_Admin\ProductController@index')->name('product_search');

Route::any('/filters_products/{vendor}/{sts}/{str}/{type}/{category_id}/{brands}/{blocked}', 'sws_Admin\ProductController@filters_products')->name('filters_products');
Route::post('/multi_delete_product', 'sws_Admin\ProductController@multi_delete_product')->name('multi_delete_product');
Route::post('/bulk_upload_product', 'sws_Admin\ProductController@bulk_upload_product')->name('bulk_upload_product');

Route::post('/bulk_upload_product_description_and_general', 'sws_Admin\ProductController@bulk_upload_product_description_and_general')->name('bulk_upload_product_description_and_general');

Route::post('/productSelection', 'sws_Admin\ProductController@productSelection')->name('productSelection');
Route::post('/bl_nblk_Product', 'sws_Admin\ProductController@bl_nblk_Product')->name('bl_nblk_Product');
});
