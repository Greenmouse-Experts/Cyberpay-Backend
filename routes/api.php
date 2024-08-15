<?php

use App\Http\Controllers\AdminController;
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

Route::post('/admin/login', [Controller::class, 'admin_login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::prefix('/admin')->group(
        function () {
            Route::get('/get/contact/form', [AdminController::class, 'getContact'])->name('getContact');
            Route::post('/delete/contact/form', [AdminController::class, 'deleteContact'])->name('deleteContact');
            Route::get('/get/career', [AdminController::class, 'getCareer'])->name('getCareer');
            Route::post('/delete/career', [AdminController::class, 'deleteCareer'])->name('deleteCareer');
            Route::get('/get/whistle/blower', [AdminController::class, 'getWhistleBlower'])->name('getWhistleBlower');
            Route::post('/delete/whistle/blower', [AdminController::class, 'deleteWhistleBlower'])->name('deleteWhistleBlower');

            // Blogs
            Route::get('/blogs', [AdminController::class, 'admin_blogs']);
            Route::post('/blog/post', [AdminController::class, 'admin_blog_post']);
            Route::post('/blog/update', [AdminController::class, 'admin_blog_update']);
            Route::post('/blog/delete', [AdminController::class, 'admin_blog_delete']);

            // Banner
            Route::get('/banners', [AdminController::class, 'admin_banners']);
            Route::post('/banner/post', [AdminController::class, 'admin_banner_post']);
            Route::post('/banner/update', [AdminController::class, 'admin_banner_update']);
            Route::post('/banner/delete', [AdminController::class, 'admin_banner_delete']);

            // FAQs
            Route::get('/faqs', [AdminController::class, 'admin_faqs']);
            Route::post('/faqs/post', [AdminController::class, 'admin_faqs_post']);
            Route::post('/faqs/update', [AdminController::class, 'admin_faqs_update']);
            Route::post('/faqs/delete', [AdminController::class, 'admin_faqs_delete']);

            // Certificate License
            Route::get('/certificate/licenses', [AdminController::class, 'admin_certificate_licenses']);
            Route::post('/certificate/license/post', [AdminController::class, 'admin_certificate_license_post']);
            Route::post('/certificate/license/update', [AdminController::class, 'admin_certificate_license_update']);
            Route::post('/certificate/license/delete', [AdminController::class, 'admin_certificate_license_delete']);

            // Certification
            Route::get('/certifications', [AdminController::class, 'admin_certifications']);
            Route::post('/certification/post', [AdminController::class, 'admin_certification_post']);
            Route::post('/certification/update', [AdminController::class, 'admin_certification_update']);
            Route::post('/certification/delete', [AdminController::class, 'admin_certification_delete']);
        }
    );
});

// Blogs
Route::get('/blogs', [Controller::class, 'get_blogs']);

// FAQs
Route::get('/faqs', [Controller::class, 'get_faqs']);

// Banner
Route::get('/banners', [Controller::class, 'get_banners']);

// Certificate License
Route::get('/certificate/licenses', [Controller::class, 'get_certificate_licenses']);

// Certification
Route::get('/certifications', [Controller::class, 'get_certifications']);
