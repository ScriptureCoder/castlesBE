<?php

Route::get('/migrate/property', 'MigrationController@migrate');
Route::get('/migrate/user', 'MigrationController@users');
Route::get('/migrate/sub', 'MigrationController@sub');
