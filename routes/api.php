<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\AdminPasswordController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\PopularTagController;
use App\Http\Controllers\CoursePageContentController;
use App\Http\Controllers\CorporateTrainingController;
use App\Http\Controllers\LiveDemoController;
use App\Http\Controllers\OnJobSupportContentController;
use App\Http\Controllers\AboutPageController;
use App\Http\Controllers\ContactPageController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\BlogPageController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseDetailsController;
use App\Http\Controllers\CourseDetailsJobAssistanceController;

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HrFaqController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\EnrollmentController;

// Header settings controller
use App\Http\Controllers\HeaderSettingController;

// Terms & Conditions Controller
use App\Http\Controllers\TermsAndConditionsController;

use App\Http\Controllers\FooterSettingsController;
use App\Http\Controllers\FormDetailsController;
use App\Http\Controllers\JobAssistanceProgramController;
use App\Http\Controllers\PlacementsReserveController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Website Frontend - No Authentication Required)
|--------------------------------------------------------------------------
| These routes are used by the public website frontend and do not require
| authentication. Only GET routes for displaying content are public.
|--------------------------------------------------------------------------
*/



// Health check
Route::get('/ping', fn() => response()->json(['ok' => true]));

// Authentication routes (public)
Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('web');
Route::post('/admin/forgot-password', [AdminPasswordController::class, 'forgot']);

// Public content display routes (GET only)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/skills', [SkillController::class, 'index']);
Route::get('/popular-tags', [PopularTagController::class, 'index']);
Route::get('/blog-categories', [BlogCategoryController::class, 'index']);

Route::get('/settings', [SettingsController::class, 'get']);
Route::get('/header-settings', [HeaderSettingController::class, 'index']);
Route::get('/footer-settings', [FooterSettingsController::class, 'index']);

Route::get('/homepage', [HomepageController::class, 'index']);
Route::get('/course-page-content', [CoursePageContentController::class, 'get']);
Route::get('/course-details/job-assistance', [CourseDetailsJobAssistanceController::class, 'index']);
Route::get('/course-details/job-assistance/{id}', [CourseDetailsJobAssistanceController::class, 'show']);

Route::get('/live-demo', [LiveDemoController::class, 'show']);
Route::post('/live-demo', [LiveDemoController::class, 'store']); // Public form submission

Route::get('/job-assistance', [JobAssistanceProgramController::class, 'index']);


Route::get('/on-job-support', [OnJobSupportContentController::class, 'show']);
Route::get('/on-job-support-page', [OnJobSupportContentController::class, 'show']); // Alias

Route::get('/corporate-training', [CorporateTrainingController::class, 'show']);
Route::get('/about-page', [AboutPageController::class, 'show']);
Route::get('/contact-page', [ContactPageController::class, 'index']);
Route::get('/seo', [SeoController::class, 'index']);
Route::get('/seo/{id}', [SeoController::class, 'show']);
Route::get('/blog-page', [BlogPageController::class, 'index']);

// Public enrollment form submission
Route::post('/enroll', [EnrollmentController::class, 'store']);

// Public course routes
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::post('/courses/{id}/review', [ReviewController::class, 'store']); // Public review submission

// Public course details routes
Route::get('/course-details', [CourseDetailsController::class, 'index']);
Route::get('/course-details/{id}', [CourseDetailsController::class, 'show']);

// Public blog routes
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{id}', [BlogController::class, 'show']);

// Public Placements Reserve route (for website frontend)
Route::get('/placements-reserve', [PlacementsReserveController::class, 'index']);

// Public FAQ routes
Route::get('/faqs', [FaqController::class, 'index']);
Route::get('/faqs/{id}', [FaqController::class, 'show']);

// Public Terms & Conditions routes
Route::get('/terms-and-conditions', [TermsAndConditionsController::class, 'show']);
Route::get('/terms', [TermsAndConditionsController::class, 'show']); // Alias
Route::get('/terms/all', [TermsAndConditionsController::class, 'index']);

// Search suggestions
Route::get('/search/suggestions', [SearchController::class, 'suggestions']);

// CORS preflight handlers
Route::options('/header-settings', function () { return response('', 200); });
Route::options('/footer-settings', function () { return response('', 200); });

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Admin Panel - auth:sanctum Required)
|--------------------------------------------------------------------------
| All admin CRUD operations require authentication via Laravel Sanctum.
| These routes are used by the admin frontend panel.
|--------------------------------------------------------------------------
*/
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('web'); // Idempotent - works even if not authenticated

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/admin/me', [AdminController::class, 'me']);

    /*
    |--------------------------------------------------------------------------
    | Admin Profile & Authentication
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/profile', [AdminController::class, 'profile']);
    Route::post('/admin/update', [AdminController::class, 'update']);
    // Route::post('/admin/logout', [AdminAuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Settings Management
    |--------------------------------------------------------------------------
    */
    Route::post('/settings', [SettingsController::class, 'update']);
    Route::post('/settings/update', [SettingsController::class, 'update']); // Legacy alias

    /*
    |--------------------------------------------------------------------------
    | Header Settings CRUD
    |--------------------------------------------------------------------------
    */
    Route::get('/header-settings/{id}', [HeaderSettingController::class, 'show']);
    Route::post('/header-settings', [HeaderSettingController::class, 'store']);
    Route::put('/header-settings', [HeaderSettingController::class, 'update']); // Update latest
    Route::put('/header-settings/{id}', [HeaderSettingController::class, 'update']); // Update specific
    Route::patch('/header-settings/{id}', [HeaderSettingController::class, 'update']);
    Route::delete('/header-settings/{id}', [HeaderSettingController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Footer Settings CRUD
    |--------------------------------------------------------------------------
    */
    Route::get('/footer-settings/{id}', [FooterSettingsController::class, 'show']);
    Route::post('/footer-settings', [FooterSettingsController::class, 'store']);
    Route::put('/footer-settings', [FooterSettingsController::class, 'update']); // Update latest
    Route::put('/footer-settings/{id}', [FooterSettingsController::class, 'update']); // Update specific
    Route::patch('/footer-settings/{id}', [FooterSettingsController::class, 'update']);
    Route::delete('/footer-settings/{id}', [FooterSettingsController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Homepage Content CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/homepage', [HomepageController::class, 'store']);
    Route::put('/homepage/{id?}', [HomepageController::class, 'update']);
    Route::patch('/homepage/{id?}', [HomepageController::class, 'update']);
    Route::delete('/homepage/{id?}', [HomepageController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Course Page Content CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/course-page-content', [CoursePageContentController::class, 'store']);
    Route::put('/course-page-content/{id?}', [CoursePageContentController::class, 'update']);
    Route::patch('/course-page-content/{id?}', [CoursePageContentController::class, 'update']);
    Route::delete('/course-page-content/{id?}', [CoursePageContentController::class, 'destroy']);
    Route::post('/course-page-content/update', [CoursePageContentController::class, 'update']); // Legacy alias

    /*
    |--------------------------------------------------------------------------
    | Course Details Job Assistance CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/course-details/job-assistance', [CourseDetailsJobAssistanceController::class, 'store']);
    Route::put('/course-details/job-assistance/{id}', [CourseDetailsJobAssistanceController::class, 'update']);

    /*
    |--------------------------------------------------------------------------
    | On Job Support Content CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/on-job-support', [OnJobSupportContentController::class, 'store']);
    Route::put('/on-job-support/{id?}', [OnJobSupportContentController::class, 'update']);
    Route::patch('/on-job-support/{id?}', [OnJobSupportContentController::class, 'update']);
    Route::delete('/on-job-support/{id?}', [OnJobSupportContentController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Corporate Training CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/corporate-training', [CorporateTrainingController::class, 'store']);
    Route::put('/corporate-training/{id?}', [CorporateTrainingController::class, 'update']);
    Route::patch('/corporate-training/{id?}', [CorporateTrainingController::class, 'update']);
    Route::delete('/corporate-training/{id?}', [CorporateTrainingController::class, 'destroy']);
    Route::post('/corporate-training/update-latest', [CorporateTrainingController::class, 'updateLatest']); // Legacy alias

    /*
    |--------------------------------------------------------------------------
    | About Page CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/about-page', [AboutPageController::class, 'store']);
    Route::put('/about-page/{id?}', [AboutPageController::class, 'update']);
    Route::patch('/about-page/{id?}', [AboutPageController::class, 'update']);
    Route::delete('/about-page/{id?}', [AboutPageController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Contact Page CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/contact-page', [ContactPageController::class, 'store']);
    Route::put('/contact-page/{id?}', [ContactPageController::class, 'update']);
    Route::patch('/contact-page/{id?}', [ContactPageController::class, 'update']);
    Route::delete('/contact-page/{id?}', [ContactPageController::class, 'destroy']);
    Route::post('/contact-page/update', [ContactPageController::class, 'update']); // Legacy alias

    /*
    |--------------------------------------------------------------------------
    | SEO Management CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/seo', [SeoController::class, 'store']);
    Route::put('/seo/{id}', [SeoController::class, 'update']);
    Route::patch('/seo/{id}', [SeoController::class, 'update']);
    Route::delete('/seo/{id}', [SeoController::class, 'destroy']);
    Route::post('/seo/{id}', [SeoController::class, 'update']); // Legacy alias

    /*
    |--------------------------------------------------------------------------
    | Blog Page CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/blog-page', [BlogPageController::class, 'store']);
    Route::put('/blog-page/{id?}', [BlogPageController::class, 'update']);
    Route::patch('/blog-page/{id?}', [BlogPageController::class, 'update']);
    Route::delete('/blog-page/{id?}', [BlogPageController::class, 'destroy']);
    Route::post('/blog-page/update', [BlogPageController::class, 'update']); // Legacy alias

    /*
    |--------------------------------------------------------------------------
    | Enrollment/Leads Management
    |--------------------------------------------------------------------------
    */
    Route::get('/leads', [EnrollmentController::class, 'index']);
    Route::get('/leads/export', [EnrollmentController::class, 'export']);
    Route::get('/leads/{id}', [EnrollmentController::class, 'show']);
    Route::delete('/leads/{id}', [EnrollmentController::class, 'destroy']);
    Route::post('/leads/delete-multiple', [EnrollmentController::class, 'deleteMultiple']);
    Route::put('/leads/{id}/status', [EnrollmentController::class, 'updateStatus']);

    /*
    |--------------------------------------------------------------------------
    | Form Details CRUD
    |--------------------------------------------------------------------------
    */
    Route::get('/form-details', [FormDetailsController::class, 'index']);
    Route::get('/form-details/{id}', [FormDetailsController::class, 'show']);
    Route::post('/form-details', [FormDetailsController::class, 'store']);
    Route::put('/form-details/{id}', [FormDetailsController::class, 'update']);
    Route::delete('/form-details/{id}', [FormDetailsController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Job Assistance Program CRUD
    |--------------------------------------------------------------------------
    */

    Route::get('/job-assistance/{id}', [JobAssistanceProgramController::class, 'show']);
    Route::post('/job-assistance', [JobAssistanceProgramController::class, 'store']);
    Route::put('/job-assistance/{id}', [JobAssistanceProgramController::class, 'update']);
    Route::delete('/job-assistance/{id}', [JobAssistanceProgramController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Placements Reserve CRUD
    |--------------------------------------------------------------------------
    */
    // GET /placements-reserve moved to public routes above
    Route::get('/placements-reserve/{id}', [PlacementsReserveController::class, 'show']);
    Route::post('/placements-reserve', [PlacementsReserveController::class, 'store']);
    Route::put('/placements-reserve/{id}', [PlacementsReserveController::class, 'update']);
    Route::delete('/placements-reserve/{id}', [PlacementsReserveController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Courses CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Course Details CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/course-details', [CourseDetailsController::class, 'store']);
    Route::put('/course-details/{id}', [CourseDetailsController::class, 'update']);
    Route::patch('/course-details/{id}', [CourseDetailsController::class, 'update']);
    Route::delete('/course-details/{id}', [CourseDetailsController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Categories CRUD (General Categories)
    |--------------------------------------------------------------------------
    */
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Blog Categories CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/categories', [BlogCategoryController::class, 'store']);
    Route::put('/categories/{id}', [BlogCategoryController::class, 'update']);
    Route::delete('/categories/{id}', [BlogCategoryController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Blogs CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::put('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Popular Tags CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/popular-tags', [PopularTagController::class, 'store']);
    Route::put('/popular-tags/{id}', [PopularTagController::class, 'update']);
    Route::delete('/popular-tags/{id}', [PopularTagController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Skills CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/skills', [SkillController::class, 'store']);
    Route::put('/skills/{id}', [SkillController::class, 'update']);
    Route::delete('/skills/{id}', [SkillController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | FAQ CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/faqs', [FaqController::class, 'store']);
    Route::put('/faqs/{id}', [FaqController::class, 'update']);
    Route::delete('/faqs/{id}', [FaqController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | HR FAQ CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/hr-faqs', [HrFaqController::class, 'store']);
    Route::put('/hr-faqs/{id}', [HrFaqController::class, 'update']);
    Route::patch('/hr-faqs/{id}', [HrFaqController::class, 'update']);
    Route::delete('/hr-faqs/{id}', [HrFaqController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Terms & Conditions CRUD
    |--------------------------------------------------------------------------
    */
    Route::post('/terms-and-conditions', [TermsAndConditionsController::class, 'store']);
    Route::post('/terms', [TermsAndConditionsController::class, 'store']); // Alias
    Route::put('/terms/{id?}', [TermsAndConditionsController::class, 'update']);
    Route::patch('/terms/{id?}', [TermsAndConditionsController::class, 'patch']);
    Route::delete('/terms/{id}', [TermsAndConditionsController::class, 'destroy']);
});
