<?php

Route::get('/migrate/property', 'MigrationController@migrate');
Route::get('/migrate/user', 'MigrationController@users');
Route::get('/migrate/sub', 'MigrationController@sub');
Route::get('/migrate/articles', 'MigrationController@articles');
Route::get('/migrate/agents', 'MigrationController@agents');

Route::get('storage/{filename}', function ($filename)
{
    $path = Storage::get($filename);

    $response = Response::make($path, 200);
    $response->header("Content-Type", "image/jpeg");

    return $response;
});


Route::get('/storage/{base}/{filename}', function ($base, $filename)
{
    $path = Storage::get("$base/$filename");

    $response = Response::make($path, 200);
    $response->header("Content-Type", "image/jpeg");

    return $response;
});

Route::get('/storage/{base}/{child}/{filename}', function ($base, $child, $filename)
{
    $path = Storage::get("$base/$child/$filename");

    $response = Response::make($path, 200);
    $response->header("Content-Type", "image/jpeg");

    return $response;
});
