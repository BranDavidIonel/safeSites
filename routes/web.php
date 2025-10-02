<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HostController;
use App\Http\Controllers\HostsDownloadController;
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
Route::get('/', [HostController::class, 'index']);

Route::post('/generate-hosts-guest', [HostsDownloadController::class, 'generateGuest'])->name('generate.hosts.guest');
Route::post('/download-hosts-guest', [HostsDownloadController::class, 'downloadGuest'])->name('download.hosts.guest');

Route::get('/', function () {
    return view('welcome');
});
