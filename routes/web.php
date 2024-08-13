<?php

use Illuminate\Support\Facades\Artisan;
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

Route::get('/', function () {
    return view('welcome');
});

// Commands
Route::get('/clear-route-cache', function() {
    Artisan::call('route:cache');
    return 'success';
});

//Remove config cache
Route::get('/clear-config-cache', function() {
    Artisan::call('config:cache');
    return 'success';
});

// Remove application cache
Route::get('/clear-app-cache', function() {
    Artisan::call('cache:clear');
    return 'success';
});

// Remove view cache
Route::get('/clear-view-cache', function() {
    Artisan::call('view:clear');
    return 'success';
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'success';
});

Route::get('/migrate', function(){
    Artisan::call('migrate', ['--force' => true]);
    return 'success';
});

Route::get('/migrate-fresh', function(){
    Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
    return 'success';
});

Route::get('/migrate-refresh', function(){
    Artisan::call('migrate:refresh', ['--seed' => true, '--force' => true]);
    return 'success';
});

Route::get('/migrate-seed', function(){
    Artisan::call('migrate', ['--seed' => true, '--force' => true]);
    return 'success';
});
