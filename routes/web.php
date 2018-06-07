<?php

Route::get('login', 'Auth\LoginController@showLoginForm')->name('loginForm');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('registerForm');

Route::get('/', 'HomeController@index')->name('home');
Route::post('sendMail', 'HomeController@sendMail')->name('send_mail');

Route::get('songs', 'SongController@index')->name('songs');

Route::get('admin/home', 'Admin\AdminHomeController@index')->name('admin.home');

Route::get('admin/songs', 'Admin\AdminSongController@index')->name('admin.songs');
Route::get('admin/songs/edit/{song_id}', 'Admin\AdminSongController@edit')->name('admin.songs.edit');
