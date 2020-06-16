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

Route::get('/','ProductController@index');
Route::get('/home', 'ProductController@index')->name('home');
Route::get('/add-product', 'ProductController@addProduct')->name('add-product');
Route::post('/save-product', 'ProductController@saveProduct');
Route::post('/pay-now', 'ProductController@payNow');
Route::post('/fetchtransactions', 'ProductController@fetchtransactions');
Route::get('cancel', 'ProductController@cancel')->name('payment.cancel');
Route::get('payment/success', 'ProductController@success')->name('payment.success');

Auth::routes();


