<?php

Route::group(['prefix' => 'admin'], function () {
    
    Route::any('/notifications', 'sws_Admin\NotificationController@notifications')->name('notifications');
    Route::any('/delete_notification/{id}', 'sws_Admin\NotificationController@delete_notification')->name('delete_notification');
    Route::any('/addNotification', 'sws_Admin\NotificationController@addNotification')->name('addNotification');
   
         

});
