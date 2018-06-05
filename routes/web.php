<?php

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::post('/sendMail', 'HomeController@sendMail')->name('send_mail');
