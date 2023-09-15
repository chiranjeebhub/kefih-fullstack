<?php

Route::group(['prefix' => 'admin'], function () {
Route::get('/users_role', 'sws_Admin\UserRoleController@lists')->name('users_role');
Route::any('/addrole', 'sws_Admin\UserRoleController@add')->name('addrole');
Route::any('/editrole', 'sws_Admin\UserRoleController@edit')->name('editrole');
Route::get('/deleterole/{id}', 'sws_Admin\UserRoleController@del')->name('deleterole');
Route::get('/role_users/{id}', 'sws_Admin\UserRoleController@role_users')->name('role_users');
Route::get('/delete_users/{id}', 'sws_Admin\UserRoleController@delete_users')->name('delete_users');
Route::any('/add_user/{id}', 'sws_Admin\UserRoleController@add_user')->name('add_user');
Route::any('/edit_user/{id}/{user_id}', 'sws_Admin\UserRoleController@edit_user')->name('edit_user');
Route::get('/change_sts_users/{id}/{sts}', 'sws_Admin\UserRoleController@change_sts_users')->name('change_sts_users');
});
