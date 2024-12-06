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
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

            Route::get('/get/contact/form', [AdminController::class, 'getContact'])->name('getContact');
            Route::post('/delete/contact/form', [AdminController::class, 'deleteContact'])->name('deleteContact');
            Route::get('/get/career', [AdminController::class, 'getCareer'])->name('getCareer');
            Route::post('/delete/career', [AdminController::class, 'deleteCareer'])->name('deleteCareer');
            Route::get('/get/whistle/blower', [AdminController::class, 'getWhistleBlower'])->name('getWhistleBlower');
            Route::post('/delete/whistle/blower', [AdminController::class, 'deleteWhistleBlower'])->name('deleteWhistleBlower');

            // Blogs
            Route::get('/blogs', [AdminController::class, 'admin_blogs']);
            Route::post('/blog/post', [AdminController::class, 'admin_blog_post']);
            Route::put('/blog/status', [AdminController::class, 'admin_blog_status']);
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

            // Office
            Route::get('/offices', [AdminController::class, 'admin_offices']);
            Route::post('/office', [AdminController::class, 'admin_office']);

            // Ads Banner
            Route::get('/ads/banners', [AdminController::class, 'admin_ads_banners']);
            Route::post('/ads/banner/post', [AdminController::class, 'admin_ads_banner_post']);
            Route::post('/ads/banner/update', [AdminController::class, 'admin_ads_banner_update']);
            Route::post('/ads/banner/delete', [AdminController::class, 'admin_ads_banner_delete']);

            // Career Role
            Route::get('/career/role', [AdminController::class, 'admin_career_roles']);
            Route::post('/career/role/post', [AdminController::class, 'admin_career_role_post']);
            Route::post('/career/role/update', [AdminController::class, 'admin_career_role_update']);
            Route::post('/career/role/delete', [AdminController::class, 'admin_career_role_delete']);

            // BreadCrumb
            Route::get('/breadcrumbs', [AdminController::class, 'admin_breadcrumbs']);
            Route::post('/breadcrumb/post', [AdminController::class, 'admin_breadcrumb_post']);
            Route::post('/breadcrumb/update', [AdminController::class, 'admin_breadcrumb_update']);
            Route::post('/breadcrumb/delete', [AdminController::class, 'admin_breadcrumb_delete']);

            // Story
            Route::get('/stories', [AdminController::class, 'admin_stories']);
            Route::post('/story/post', [AdminController::class, 'admin_story_post']);
            Route::post('/story/update', [AdminController::class, 'admin_story_update']);
            Route::post('/story/delete', [AdminController::class, 'admin_story_delete']);

            // People
            Route::get('/peoples', [AdminController::class, 'admin_peoples']);
            Route::post('/people/post', [AdminController::class, 'admin_people_post']);
            Route::post('/people/update', [AdminController::class, 'admin_people_update']);
            Route::post('/people/delete', [AdminController::class, 'admin_people_delete']);

            Route::get('/sub-admins', [AdminController::class, 'fetchSubAdmins']);
            Route::post('/sub-admin/create', [AdminController::class, 'addSubAdmin']);
            Route::post('/sub-admin/update', [AdminController::class, 'updateSubAdmin']);
            Route::post('/sub-admin/delete', [AdminController::class, 'deleteSubAdmin']);
            Route::post('/sub-admin/toggle-status', [AdminController::class, 'toggleSubAdminStatus']);
            Route::post('/sub-admin/resend-login', [AdminController::class, 'resendLoginDetails']);

            // SEO
            Route::get('/seos', [AdminController::class, 'admin_seos']);
            Route::post('/seo/post', [AdminController::class, 'admin_seo_post']);
            Route::post('/seo/update', [AdminController::class, 'admin_seo_update']);
            Route::post('/seo/delete', [AdminController::class, 'admin_seo_delete']);

            // Solutions
            Route::get('/solutions', [AdminController::class, 'admin_solutions']);
            Route::post('/solution/post', [AdminController::class, 'admin_solution_post']);
            Route::post('/solution/update', [AdminController::class, 'admin_solution_update']);
            Route::post('/solution/toggle-status', [AdminController::class, 'toggleSolutionPageStatus']);
            Route::post('/solution/delete', [AdminController::class, 'admin_solution_delete']);

            // About
            Route::get('/abouts', [AdminController::class, 'admin_abouts']);
            Route::post('/about', [AdminController::class, 'admin_about']);

            // Why Cyberpay
            Route::get('/why/cyberpay', [AdminController::class, 'admin_why_cyberpays']);
            Route::post('/why', [AdminController::class, 'admin_why']);

            // Terms and Conditions
            Route::get('/terms/conditions', [AdminController::class, 'admin_terms_conditions']);
            Route::post('/term/condition', [AdminController::class, 'admin_term_condition']);

            // Privacy Policies
            Route::get('/privacy/policies', [AdminController::class, 'admin_privacy_policies']);
            Route::post('/privacy/policy', [AdminController::class, 'admin_privacy_policy']);

            // Market Place Solution
            Route::get('/solution/market/place', [AdminController::class, 'admin_solution_market_place']);
            Route::post('/market/place', [AdminController::class, 'admin_market_place']);

            // Mobile App Solution
            Route::get('/solution/mobile/app', [AdminController::class, 'admin_solution_mobile_app']);
            Route::post('/mobile/app', [AdminController::class, 'admin_mobile_app']);

            // Payment Link Solution
            Route::get('/solution/payment/link', [AdminController::class, 'admin_solution_payment_link']);
            Route::post('/payment/link', [AdminController::class, 'admin_payment_link']);

            // USSD Collections Solution
            Route::get('/solution/ussd/collection', [AdminController::class, 'admin_solution_ussd_collection']);
            Route::post('/ussd/collection', [AdminController::class, 'admin_ussd_collection']);

            // Direct Debit Solution
            Route::get('/solution/direct/debit', [AdminController::class, 'admin_solution_direct_debit']);
            Route::post('/direct/debit', [AdminController::class, 'admin_direct_debit']);

            // POS Service
            Route::get('/solution/pos/service', [AdminController::class, 'admin_solution_pos_service']);
            Route::post('/solution/pos/service/post', [AdminController::class, 'admin_solution_pos_service_post']);
            Route::post('/solution/pos/service/update', [AdminController::class, 'admin_solution_pos_service_update']);
            Route::post('/solution/pos/service/delete', [AdminController::class, 'admin_solution_pos_service_delete']);

            // Bulk Sms Solution
            Route::get('/solution/bulk/sms', [AdminController::class, 'admin_solution_bulk_sms']);
            Route::post('/bulk/sms', [AdminController::class, 'admin_bulk_sms']);

            // Pen Remit Solution
            Route::get('/solution/pen/remit', [AdminController::class, 'admin_solution_pen_remit']);
            Route::post('/pen/remit', [AdminController::class, 'admin_pen_remit']);

            // Payment Solution
            Route::get('/solution/payment', [AdminController::class, 'admin_solution_payment']);
            Route::post('/payment', [AdminController::class, 'admin_payment']);

            // Disbursement Solution
            Route::get('/solution/disbursement', [AdminController::class, 'admin_solution_disbursement']);
            Route::post('/disbursement', [AdminController::class, 'admin_disbursement']);

            // BCMS Policy Statement
            Route::get('/bcms/policy/statements', [AdminController::class, 'admin_bcms_policy_statements']);
            Route::post('/bcms/policy/statement', [AdminController::class, 'admin_bcms_policy_statement']);

            // ISMS Policy Statement
            Route::get('/isms/policy/statements', [AdminController::class, 'admin_isms_policy_statements']);
            Route::post('/isms/policy/statement', [AdminController::class, 'admin_isms_policy_statement']);

            // Menus
            Route::get('/menus', [AdminController::class, 'admin_menus']);
            Route::post('/menus/post', [AdminController::class, 'admin_menus_post']);
            Route::put('/menus/toggle/status', [AdminController::class, 'admin_menus_toggle_status']);
            Route::post('/menus/delete', [AdminController::class, 'admin_menus_delete']);
        }
    );
});

// Menus
Route::get('/menus', [Controller::class, 'get_menus']);

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

// Office
Route::get('/office', [Controller::class, 'office']);

// Ads Banner
Route::get('/ads/banners', [Controller::class, 'ads_banners']);

// Career Role
Route::get('/career/role', [Controller::class, 'career_roles']);

// BreadCrumb
Route::get('/breadcrumbs', [Controller::class, 'breadcrumbs']);

// Story
Route::get('/stories', [Controller::class, 'stories']);

// People
Route::get('/peoples', [Controller::class, 'peoples']);

// SEO
Route::get('/seos', [Controller::class, 'seos']);

// About
Route::get('/abouts', [Controller::class, 'abouts']);

// Why Cyberpay
Route::get('/why/cyberpay', [Controller::class, 'why_cyberpay']);

// Terms and Conditions
Route::get('/terms/conditions', [Controller::class, 'terms_conditions']);

// Privacy Policies
Route::get('/privacy/policies', [Controller::class, 'privacy_policies']);

// Solutions
Route::get('/solution/market/place', [Controller::class, 'solution_market_place']);
Route::get('/solution/mobile/app', [Controller::class, 'solution_mobile_app']);
Route::get('/solution/payment/link', [Controller::class, 'solution_payment_link']);
Route::get('/solution/ussd/collection', [Controller::class, 'solution_ussd_collection']);
Route::get('/solution/direct/debit', [Controller::class, 'solution_direct_debit']);
Route::get('/solution/pos/service', [Controller::class, 'solution_pos_service']);
Route::get('/solution/bulk/sms', [Controller::class, 'solution_bulk_sms']);
Route::get('/solution/pen/remit', [Controller::class, 'solution_pen_remit']);
Route::get('/solution/payment', [Controller::class, 'solution_payment']);
Route::get('/solution/disbursement', [Controller::class, 'solution_disbursement']);

// BCMS Policy Statement
Route::get('/bcms/policy/statements', [Controller::class, 'bcms_policy_statements']);

// ISMS Policy Statement
Route::get('/isms/policy/statements', [Controller::class, 'isms_policy_statements']);
