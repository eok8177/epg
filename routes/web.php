<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes(['verify' => true]);

Route::get('/', function () {
    return view('welcome');
});

Route::group([
    'as' => 'user.',
    'namespace' => 'User',
    // 'prefix' => 'user',
    'middleware' => ['roles', 'verified'],
    'roles' =>['user','admin']
], function() {
    Route::get('home', function () {
        return view('cabinet',[
            'client_id'=>auth()->user()->id
        ]);
    });
    Route::get('/home/chanel/{chanel}', function () {
        return view('cabinet',[
            'client_id'=>auth()->user()->id
        ]);
    });
});