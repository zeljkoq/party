<?php

Route::post('login', 'Auth\Api\LoginController@login')->name('login');
Route::post('register', 'Auth\Api\RegisterController@register')->name('register');

Route::middleware('jwt.auth')->group(function () {
    Route::get('songs', [
        'uses' => 'Api\SongController@index',
        'as' => 'songs.index',
    ]);

    Route::get('admin/songs', [
        'uses' => 'Admin\Api\AdminSongController@index',
        'as' => 'admin.songs.index',
        'middleware' => 'roles',
        'roles' => ['Admin', 'DJ']
    ]);
    Route::post('admin/songs/store', [
        'uses' => 'Admin\Api\AdminSongController@store',
        'as' => 'admin.songs.store',
        'middleware' => 'roles',
        'roles' => ['Admin', 'DJ']
    ]);
    Route::get('admin/songs/show/{song_id}', [
        'uses' => 'Admin\Api\AdminSongController@show',
        'as' => 'admin.songs.show',
        'middleware' => 'roles',
        'roles' => ['Admin', 'DJ']
    ]);
    Route::put('admin/songs/update/{song_id}', [
        'uses' => 'Admin\Api\AdminSongController@update',
        'as' => 'admin.songs.update',
        'middleware' => 'roles',
        'roles' => ['Admin', 'DJ']
    ]);
    Route::delete('admin/delete/{song_id}', [
        'uses' => 'Admin\Api\AdminSongController@delete',
        'as' => 'admin.songs.delete',
        'middleware' => 'roles',
        'roles' => ['Admin', 'DJ']
    ]);
});
