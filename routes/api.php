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
Route::get('/localities/{state_id}', 'Statics\StaticController@localities');
Route::get('/labels', 'Statics\StaticController@labels');
Route::get('/features', 'Statics\StaticController@features');
Route::get('/property_statuses', 'Statics\StaticController@statuses');
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
        Route::post('/save', 'Admin\PropertiesController@save');
        Route::get('/trash', 'Admin\PropertiesController@trash');
        Route::get('/{id}', 'Admin\PropertiesController@view');
        Route::get('/{id}/gallery', 'Admin\PropertiesController@gallery');
        Route::post('/{id}/gallery/add', 'Admin\PropertiesController@addToGallery');
        Route::post('/{id}/gallery/feature', 'Admin\PropertiesController@featureImage');
        Route::delete('/{id}/gallery/remove', 'Admin\PropertiesController@removeFromGallery');
        Route::post('/approve/{ids}', 'Admin\PropertiesController@approve');
        Route::post('/disapprove/{ids}', 'Admin\PropertiesController@disapprove');
        Route::delete('/property/delete/{ids}', 'Admin\PropertiesController@delete');
        Route::delete('/property/destroy/{ids}', 'Admin\PropertiesController@destroy');
    });

    /*Users end-points*/
    Route::group(['prefix'=>'users'], function() {

    });
});

/**Agent end-points*/
Route::group(['prefix'=>'agent', 'middleware'=>['auth:api','agent']], function() {
    /*properties end-points*/
    Route::group(['prefix'=>'properties'], function() {
        Route::get('/', 'Agent\PropertiesController@index');
        Route::post('/save', 'Agent\PropertiesController@save');
        Route::get('/{id}', 'Agent\PropertiesController@view');
        Route::get('/{id}/gallery', 'Admin\PropertiesController@gallery');
        Route::post('/{id}/gallery/add', 'Admin\PropertiesController@addToGallery');
        Route::post('/{id}/gallery/feature', 'Admin\PropertiesController@featureImage');
        Route::delete('/{id}/gallery/remove', 'Admin\PropertiesController@removeFromGallery');
        Route::delete('/property/delete/{ids}', 'Admin\PropertiesController@delete');
    });
});


