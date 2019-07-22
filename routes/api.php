<?php

/**Authentication end-points*/
Route::post('/register', 'Auth\API\RegisterController@register');
Route::post('/login', 'Auth\API\RegisterController@login');
Route::post('/oauth', 'Auth\API\RegisterController@oauth');
Route::post('/resend', 'Auth\API\RegisterController@resend')->middleware(['auth:api']);
Route::post('/activate/{token}', 'Auth\API\RegisterController@activate')->middleware(['auth:api']);
Route::post('/forgot_password', 'Auth\API\PasswordController@forgotPassword');
Route::post('/change_password', 'Auth\API\PasswordController@changePassword')->middleware(['auth:api']);
Route::post('/reset_password/{token}', 'Auth\API\PasswordController@resetPassword');

/*send email*/
Route::post('/send_email', 'EmailController@send')->middleware(['auth:api']);

/*Images end-point*/
Route::post('/upload_images', 'ImageController@upload')->middleware(['auth:api']);
Route::get('/my_images', 'ImageController@myImages')->middleware(['auth:api']);

/**Properties listing and search*/
Route::group(['prefix'=>'properties'], function() {
    Route::get('/', 'PropertiesController@index');
    Route::get('/{agent_id}/', 'PropertiesController@agent');
    Route::get('/filter', 'PropertiesController@filter');
});
Route::get('/property/{slug}', 'PropertiesController@view')->middleware(['if_auth']);

Route::get('/saved_properties', 'PropertiesController@viewSaved')->middleware(['auth:api']);
Route::post('/save_property', 'PropertiesController@save')->middleware(['auth:api']);


/**Magazine end-points*/
Route::get('/magazines', 'Admin\MagazineController@index');
Route::get('/magazine/download/{id}', 'Admin\MagazineController@download');

/**advert end-point*/
Route::get('/adverts', 'AdvertController@list');
Route::get('/advert/{id}', 'AdvertController@properties');

/** Report and request property*/
Route::post('/report_property', 'PropertiesController@report')->middleware(['auth:api']);
Route::post('/request_property', 'PropertiesController@request')->middleware(['if_auth']);

/**Search and Alert end-points*/
Route::get('/search', 'SearchController@search');
Route::post('/alert/save', 'AlertController@save')->middleware(['if_auth']);
Route::get('/alerts', 'AlertController@index')->middleware(['auth:api']);
Route::delete('/alert/delete/{id}', 'AlertController@delete')->middleware(['auth:api']);


/**Property Advice*/
Route::get('/property_advise', 'ArticlesController@categories');
Route::get('/property_advice', 'ArticlesController@categories');
Route::get('/articles/{slug}', 'ArticlesController@adviceArticles');
Route::post('/article/comment', 'ArticlesController@comment')->middleware(['if_auth']);
Route::get('/articles', 'ArticlesController@articles');
Route::get('/article/{slug}', 'ArticlesController@view');

/**Static end-points*/
Route::get('/countries', 'Statics\StaticController@countries');
Route::get('/states', 'Statics\StaticController@states');
Route::get('/localities/{state_id}', 'Statics\StaticController@localities');
Route::get('/labels', 'Statics\StaticController@labels');
Route::get('/features', 'Statics\StaticController@features');
Route::get('/property_statuses', 'Statics\StaticController@statuses');
Route::get('/property_types', 'Statics\StaticController@types');


Route::group(['prefix'=>'user', 'middleware'=>['auth:api']], function() {
    Route::get('/', 'UsersController@index');
    Route::post('/', 'UsersController@update');
    Route::get('/check', 'UsersController@check');
    Route::post('/image', 'UsersController@picture');
});

/**Admin end-points*/
Route::group(['prefix'=>'admin', 'middleware'=>['auth:api','admin']], function() {
    /*Properties end-points*/

    Route::group(['prefix'=>'properties'], function() {
        Route::get('/', 'Admin\PropertiesController@index');
        Route::post('/save', 'Admin\PropertiesController@save');
        Route::post('/save/multiple', 'Admin\PropertiesController@multiple');

        Route::get('/reports', 'Admin\PropertiesController@reports');
        Route::get('/requests', 'Admin\PropertiesController@requests');
        Route::get('/{id}/reports', 'Admin\PropertiesController@propertyReports');

        Route::get('/trash', 'Admin\PropertiesController@trash');
        Route::get('/{id}', 'Admin\PropertiesController@view');

        Route::get('/{id}/pictures', 'Agent\PropertiesController@gallery');
        Route::post('/{id}/upload', 'Agent\PropertiesController@addToGallery');
        Route::post('/{id}/feature', 'Agent\PropertiesController@featureImage');
        Route::delete('/{id}/picture/delete/{image}', 'Agent\PropertiesController@removeFromGallery');

        Route::post('/approve', 'Admin\PropertiesController@approve');
        Route::post('/disapprove', 'Admin\PropertiesController@disapprove');
        Route::post('/delete', 'Admin\PropertiesController@delete');
        Route::post('/destroy', 'Admin\PropertiesController@destroy');
    });

    /*Users end-points*/
    Route::group(['prefix'=>'users'], function() {
        Route::get('/', 'Admin\UsersController@index');
        Route::post('/create', 'Admin\UsersController@create');
        Route::post('/edit/{id}', 'Admin\UsersController@edit');
        Route::get('/suspended', 'Admin\UsersController@suspended');
        Route::post('/{id}/activate', 'Admin\UsersController@activate');
        Route::post('/{id}/suspend', 'Admin\UsersController@suspend');
        Route::delete('/{id}/delete', 'Admin\UsersController@delete');
        Route::post('/{id}/restore', 'Admin\UsersController@restore');
        Route::get('/{id}', 'Admin\UsersController@view');
    });

    /*Analytics end-points*/
    Route::group(['prefix'=>'analytics'], function() {
        Route::get('/', 'Admin\AnalyticsController@index');
        Route::get('/project/{id}', 'Admin\AnalyticsController@projectViews');
        Route::get('/project', 'Admin\AnalyticsController@project');
    });

    /*Property advise end-points*/
    Route::post('/article/save', 'ArticlesController@save');
    Route::delete('/article/delete/{id}', 'ArticlesController@deleteArticle');
    Route::delete('/comment/delete/{id}', 'ArticlesController@deleteComment');

    Route::group(['prefix'=>'newsletter'], function() {
        Route::post('/send', 'Admin\NewsletterController@send');
    });

    /*send newsletter*/
    Route::post('/send_newsletter', 'Admin\NewsletterController@send');

    /*Magazine end-points*/
    Route::get('/magazines', 'Admin\MagazineController@index');
    Route::post('/magazine/save', 'Admin\MagazineController@save');
    Route::post('/magazine/download/{id}', 'Admin\MagazineController@download');
    Route::delete('/magazines/delete', 'Admin\MagazineController@delete');


    /*advert end-point*/
    Route::get('/adverts', 'Admin\AdvertController@list');
    Route::post('/advert/save', 'Admin\AdvertController@save');
    Route::get('/advert/{id}', 'Admin\AdvertController@properties');
    Route::post('/advert/add_properties/{id}', 'Admin\AdvertController@addProperties');
    Route::post('/adverts/refresh', 'Admin\AdvertController@refresh');
    Route::delete('/adverts/delete', 'Admin\AdvertController@delete');


});

/**Agent end-points*/
Route::group(['prefix'=>'agent', 'middleware'=>['auth:api','agent']], function() {
    /*properties end-points*/
    Route::group(['prefix'=>'properties'], function() {
        Route::get('/', 'Agent\PropertiesController@index');
        Route::post('/save', 'Agent\PropertiesController@save');
        Route::get('/{id}', 'Agent\PropertiesController@view');
        Route::get('/{id}/pictures', 'Agent\PropertiesController@gallery');
        Route::post('/{id}/upload', 'Agent\PropertiesController@addToGallery');
        Route::post('/{id}/feature', 'Agent\PropertiesController@featureImage');
        Route::delete('/{id}/picture/delete/{image}', 'Agent\PropertiesController@removeFromGallery');
        Route::delete('/delete/{id}', 'Agent\PropertiesController@delete');
    });

    /*Analytics end-points*/
    Route::group(['prefix'=>'analytics'], function() {
        Route::get('/', 'Agent\AnalyticsController@index');
        Route::get('/project/{id}', 'Agent\AnalyticsController@projectViews');
        Route::get('/project', 'Agent\AnalyticsController@project');
    });
});


/**Agent end-points*/
Route::group(['middleware'=>['auth:api','super_admin']], function() {
    Route::get('/migration', 'Admin\OfflineController@getAll');
    Route::post('/migrate', 'Admin\OfflineController@migrate');
});






