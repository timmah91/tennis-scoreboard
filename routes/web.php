<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

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

//Route::get('/', function () {
//    return \App\Http\Controllers\GameController::class;
//});

//Route::controller(\App\Http\Controllers\GameController::class)->group(function () {
//    Route::get('/', 'newGame');
//    Route::get('/scoreboard', 'startGame');
//    Route::post('/scoreboard', '');
//});


Route::controller(\App\Http\Controllers\ScoreboardController::class)->group(function () {
    Route::get('/', 'showGame');
    Route::post('/', 'scoreAPoint');
});
