<?php

use App\Http\Controllers\Images;
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

Route::get('/i/gradient/{start}/{end}', [Images::class, 'renderGradient'])
    ->where(['start' => '[A-Fa-f0-9]{6}', 'end' => '[A-Fa-f0-9]{6}']);
Route::get('/i/channel/{gradientStart}/{gradientEnd}/{emoji}', [Images::class, 'renderChannelIcon'])
    ->where(['gradientStart' => '[A-Fa-f0-9]{6}', 'gradientEnd' => '[A-Fa-f0-9]{6}']);
Route::get('/i/{image}', [Images::class, 'generate'])->where('image', '(.*)');
