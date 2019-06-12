<?php

Route::get('/testimony/developers', 'Developer\TestimonialController@getDevTestimonies');
Route::group(['prefix'=>'client'], function() {
    Route::get('countries', 'StaticController@getCountries');
});