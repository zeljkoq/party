<?php

Route::get('login', 'Auth\LoginController@showLoginForm')->name('loginForm');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('registerForm');

Route::get('/', 'HomeController@index')->name('home');
Route::post('sendMail', 'HomeController@sendMail')->name('send_mail');

Route::get('songs', 'SongController@index')->name('songs');

Route::get('admin/home', 'Admin\AdminHomeController@index')->name('admin.home');

Route::get('admin/songs', 'Admin\AdminSongController@index')->name('admin.songs');

Route::get('admin/parties', 'Admin\AdminPartyController@index')->name('admin.parties');
Route::get('admin/parties/edit/{party_id}', 'Admin\AdminPartyController@edit')->name('admin.parties.edit');
