<?php

use Illuminate\Support\Facades\Route;

//namespace App\Http\Controllers\SalesforceController;

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

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/install', [ SalesforceController::class, 'index' ]);
//Route::get('/oauth/callback', [ SalesforceController::class, 'oauthCallback' ]);

Route::get('/archivos','ArchivoController@index');
Route::post('store-archivo', 'ArchivoController@store')->name('archivo.store');
Route::get('downloacd-archivo', 'ArchivoController@downloadArchivo')->name('archivo.download');