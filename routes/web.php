<?php

Route::get('/migrate/property', 'MigrationController@migrate');
Route::get('/migrate/user', 'MigrationController@users');
Route::get('/migrate/sub', 'MigrationController@sub');
Route::get('/migrate/articles', 'MigrationController@articles');
Route::get('/migrate/agents', 'MigrationController@agents');
