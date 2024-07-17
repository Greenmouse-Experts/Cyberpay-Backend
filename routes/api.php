<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return response([
        'status' => true,
        'message' => 'You are now on Cyberpay API endpoints'
    ]);
});

Route::get('/login', function () {
    return response([
        'status' => false,
        'message' => 'Token Required!'
    ]);
})->name('login');

Route::post('/submit/contact/form', [Controller::class, 'submitContact'])->name('submitContact');
Route::post('/submit/career', [Controller::class, 'submitCareer'])->name('submitCareer');
Route::post('/submit/whistle/blower', [Controller::class, 'submitWhistleBlower'])->name('submitWhistleBlower');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
