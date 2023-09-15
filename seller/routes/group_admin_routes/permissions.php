<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/permissions', 'sws_Admin\PermissionController@index')->name('permissions');
Route::any('/add_permissions', 'sws_Admin\PermissionController@add_permissions')->name('addpermissions');
Route::any('/edit_permissions/{id}', 'sws_Admin\PermissionController@edit_permissions')->name('editpermissions');
Route::get('/delete_permissions/{id}', 'sws_Admin\PermissionController@delete_permissions')->name('deletepermissions');
});