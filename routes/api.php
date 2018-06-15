<?php

Route::post('login', 'Auth\Api\LoginController@login')->name('login');
Route::post('register', 'Auth\Api\RegisterController@register')->name('register');
Route::get('register/roles', 'Auth\Api\RegisterController@getRoles')->name('register.roles');

Route::middleware('jwt.auth')->group(function () {
    Route::get('home', [
        'uses' => 'Api\HomeController@index',
        'as' => 'home.index',
    ]);
    Route::get('home/routes', [
        'uses' => 'Api\HomeController@routes',
        'as' => 'home.routes',
    ]);

    Route::get('parties/sing-up/{party_id}', [
        'uses' => 'Api\PartyController@singUp',
        'as' => 'parties.sing.up',
    ]);
    Route::get('parties/sing-out/{party_id}', [
        'uses' => 'Api\PartyController@singOut',
        'as' => 'parties.sing.out',
    ]);
    Route::get('parties', [
        'uses' => 'Api\PartyController@index',
        'as' => 'parties.index',
    ]);

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
    Route::post('admin/songs', [
        'uses' => 'Admin\Api\AdminSongController@store',
        'as' => 'admin.songs.store',
        'middleware' => 'roles',
        'roles' => ['Admin', 'DJ']
    ]);
    Route::get('admin/songs/{song_id}', [
        'uses' => 'Admin\Api\AdminSongController@show',
        'as' => 'admin.songs.show',
        'middleware' => 'roles',
        'roles' => ['Admin', 'DJ']
    ]);
    Route::put('admin/songs', [
        'uses' => 'Admin\Api\AdminSongController@update',
        'as' => 'admin.songs.update',
        'middleware' => 'roles',
        'roles' => ['Admin', 'DJ']
    ]);
    Route::delete('admin/songs/{song_id}', [
        'uses' => 'Admin\Api\AdminSongController@delete',
        'as' => 'admin.songs.delete',
        'middleware' => 'roles',
        'roles' => ['Admin', 'DJ']
    ]);

    Route::get('admin/parties', [
        'uses' => 'Admin\Api\AdminPartyController@index',
        'as' => 'admin.parties.index',
        'middleware' => 'roles',
        'roles' => ['Admin', 'Party Maker']
    ]);
    Route::post('admin/parties', [
        'uses' => 'Admin\Api\AdminPartyController@store',
        'as' => 'admin.parties.store',
        'middleware' => 'roles',
        'roles' => ['Admin', 'Party Maker']
    ]);
    Route::get('admin/parties/{party_id}', [
        'uses' => 'Admin\Api\AdminPartyController@show',
        'as' => 'admin.parties.show',
        'middleware' => 'roles',
        'roles' => ['Admin', 'Party Maker']
    ]);
    Route::put('admin/parties', [
        'uses' => 'Admin\Api\AdminPartyController@update',
        'as' => 'admin.parties.update',
        'middleware' => 'roles',
        'roles' => ['Admin', 'Party Maker']
    ]);
    Route::delete('admin/parties/{party_id}', [
        'uses' => 'Admin\Api\AdminPartyController@delete',
        'as' => 'admin.parties.delete',
        'middleware' => 'roles',
        'roles' => ['Admin', 'Party Maker']
    ]);
    Route::get('admin/parties/start/{party_id}', [
        'uses' => 'Admin\Api\AdminPartyController@start',
        'as' => 'admin.parties.start',
        'middleware' => 'roles',
        'roles' => ['Admin', 'Party Maker']
    ]);
    Route::get('admin/parties/details/{party_id}', [
        'uses' => 'Admin\Api\AdminPartyController@details',
        'as' => 'admin.parties.details',
        'middleware' => 'roles',
        'roles' => ['Admin', 'Party Maker']
    ]);
});
