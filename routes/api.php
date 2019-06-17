<?php

/**Authentication end-points*/
Route::post('/register', 'Auth\API\RegisterController@register');
Route::post('/login', 'Auth\API\RegisterController@login');
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


Route::group(['prefix'=>'user', 'middleware'=>['auth:api']], function() {
    Route::get('/', 'UserController@index');
    Route::post('/', 'UserController@update');
});

/**Admin end-points*/
Route::group(['prefix'=>'admin', 'middleware'=>['auth:api','admin']], function() {

    /*Properties end-points*/
    Route::group(['prefix'=>'properties'], function() {
        Route::get('/', 'Admin\PropertiesController@index');
        Route::get('/trash', 'Admin\PropertiesController@trash');
        Route::get('/{id}', 'Admin\PropertiesController@view');
        Route::post('/', 'Admin\PropertiesController@save');
        Route::post('/approve/{ids}', 'Admin\PropertiesController@approve');
        Route::post('/disapprove/{ids}', 'Admin\PropertiesController@disapprove');
        Route::delete('/property/delete/{ids}', 'Admin\PropertiesController@delete');
        Route::delete('/property/destroy/{ids}', 'Admin\PropertiesController@destroy');
    });

    /*Users end-points*/
    Route::group(['prefix'=>'users'], function() {

    });
});
Route::post('/upload', 'Admin\PropertiesController@image');


