<?php

Route::post('login', 'Auth\Api\LoginController@login')->name('login');
Route::post('register', 'Auth\Api\RegisterController@register')->name('register');

Route::middleware('jwt.auth')->group(function () {
    Route::apiResource('songs', 'Api\SongController');
    Route::apiResource('admin-songs', 'Admin\Api\AdminSongController');
});
