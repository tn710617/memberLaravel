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

Route::get('/home', 'MemberController@index')->name('home');
Route::get('/register', 'RegistrationController@create');
Route::post('/register', 'RegistrationController@store');

Route::get('/login', 'SessionsController@create')->name('login');
Route::post('/login', 'SessionsController@store');
Route::get('/logout', 'SessionsController@destroy');

Route::get('/edit', 'MemberController@edit')->middleware('auth');
Route::put('/edit', 'MemberController@update');

Route::get('/admin', 'AdminController@index')->middleware('is_admin')->name('admin');
Route::post('/delete', 'AdminController@delete');
