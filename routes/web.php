<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');
Route::post('login', 'UserController@login');
Route::post('logout', 'UserController@logout');
Route::post('register', 'UserController@register');
Route::post('forgot', 'UserController@forgotPassword');
Route::post('forgot_verify', 'UserController@forgotPasswordVerify');
Route::post('edit-account', 'UserController@do_edit_account');
Route::post('edit-password', 'UserController@do_edit_password');
