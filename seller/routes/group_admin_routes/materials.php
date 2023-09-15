<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/materials', 'sws_Admin\MaterialsController@lists')->name('materials');

Route::get('/export_materials', 'sws_Admin\MaterialsController@export_materials')->name('export_materials');

Route::any('/addmaterial', 'sws_Admin\MaterialsController@add')->name('addmaterial');
Route::any('/editmaterial/{id}', 'sws_Admin\MaterialsController@edit')->name('editmaterial');
Route::get('/deletematerial/{id}', 'sws_Admin\MaterialsController@del')->name('deletematerial');
Route::get('/material_sts/{id}/{sts}', 'sws_Admin\MaterialsController@material_sts')->name('material_sts');
Route::any('/material_search/{str}/{status}', 'sws_Admin\MaterialsController@lists')->name('material_search');
Route::any('/material_search_str/{str}', 'sws_Admin\MaterialsController@lists')->name('material_search_str');
Route::post('/multi_delete_material', 'sws_Admin\MaterialsController@multi_delete_material')->name('multi_delete_material');
});