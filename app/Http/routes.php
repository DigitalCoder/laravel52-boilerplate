<?php

/***** Public Pages *****/
Route::group(['middleware' => ['web']], function () {
    Route::get('/', ['as' => 'home', 'uses' => 'IndexController@getIndex']);

    // Built-in authentication routes
    Route::auth();
});

/***** Admin Pages *****/
Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth', 'usertype:admin']], function () {
    Route::get('/', ['as' => 'admin-home', 'uses' => 'Admin\IndexController@index']);
});

/***** User Dashboard Pages *****/
Route::group(['prefix' => 'dashboard', 'middleware' => ['web', 'auth', 'usertype:user']], function () {
    Route::get('/', ['as' => 'user-dashboard', 'uses' => 'Dashboard\IndexController@index']);
});

/***** Other Password Protected Pages *****/
Route::group(['middleware' => ['web', 'auth']], function () {
    // Redirect to the correct home page by user type
    Route::get('home', 'Auth\AuthController@homeRedirect');
});
