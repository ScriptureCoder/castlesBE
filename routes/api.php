<?php

/**Authentication end-points*/
Route::post('/register', 'Auth\API\RegisterController@register');
Route::post('/resend', 'Auth\API\RegisterController@resend')->middleware(['auth:api']);
Route::post('/activation/{token}', 'Auth\API\RegisterController@activate')->middleware(['auth:api']);
Route::get('/forgot_password', 'Auth\API\PasswordController@forgotPassword');
Route::post('/reset_password', 'Auth\API\PasswordController@resetPassword');
Route::post('/change_password', 'Auth\API\PasswordController@resetPassword')->middleware(['auth:api']);

Route::group(['prefix'=>'client'], function() {
});

/**Static end-points*/
Route::get('/countries', 'Statics\StaticController@countries');
Route::get('/states', 'Statics\StaticController@states');
Route::get('/cities/{state_id}', 'Statics\StaticController@cities');
Route::get('/labels', 'Statics\StaticController@labels');
Route::get('/features', 'Statics\StaticController@features');
Route::get('/property_status', 'Statics\StaticController@statuses');
Route::get('/property_types', 'Statics\StaticController@types');


