<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CallSettingController;
use App\Http\Controllers\Admin\ApiSettingController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\FlightEnquiryController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\SchemaController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Frontend\HomeController as FrontHomeController;
use App\Http\Controllers\Frontend\FlightController as FrontFlightController;
use App\Http\Controllers\Frontend\OfferController as FrontOfferController;
use App\Http\Controllers\Frontend\BlogController as FrontBlogController;
use App\Http\Controllers\Frontend\DestinationController as FrontDestinationController;
use App\Http\Controllers\Frontend\PageController as FrontPageController;
use App\Http\Controllers\Frontend\FaqController as FrontFaqController;
use App\Http\Controllers\Frontend\ContactController as FrontContactController;
use App\Http\Controllers\Frontend\NewsletterController as FrontNewsletterController;
use App\Http\Controllers\Frontend\SitemapController as FrontSitemapController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===================== FRONTEND ROUTES =====================
Route::get('/', [FrontHomeController::class, 'index'])->name('home');
Route::get('/flights/search', [FrontFlightController::class, 'search'])->name('flights.search');
Route::get('/flights/results', [FrontFlightController::class, 'results'])->name('flights.results');
Route::get('/flights/details/{id}', [FrontFlightController::class, 'details'])->name('flights.details');
Route::get('/booking-enquiry', [FrontFlightController::class, 'enquiryForm'])->name('booking.enquiry');
Route::post('/booking-enquiry', [FrontFlightController::class, 'submitEnquiry'])->name('booking.submit');
Route::get('/offers', [FrontOfferController::class, 'index'])->name('offers.index');
Route::get('/offers/{slug}', [FrontOfferController::class, 'show'])->name('offers.show');
Route::get('/blog', [FrontBlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [FrontBlogController::class, 'show'])->name('blog.show');
Route::get('/destinations', [FrontDestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{slug}', [FrontDestinationController::class, 'show'])->name('destinations.show');
Route::get('/about', [FrontPageController::class, 'about'])->name('about');
Route::get('/contact', [FrontPageController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontContactController::class, 'submit'])->name('contact.submit');
Route::get('/faq', [FrontFaqController::class, 'index'])->name('faq');
Route::get('/privacy-policy', [FrontPageController::class, 'privacy'])->name('privacy');
Route::get('/terms-conditions', [FrontPageController::class, 'terms'])->name('terms');
Route::post('/newsletter/subscribe', [FrontNewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::post('/lead/submit', [FrontHomeController::class, 'submitLead'])->name('lead.submit');
Route::get('/sitemap.xml', [FrontSitemapController::class, 'index']);
Route::get('/robots.txt', [FrontSitemapController::class, 'robots']);

// Admin Authentication Routes
Route::group(['prefix' => 'admin'], function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// Admin Protected Routes
Route::group(['prefix' => 'admin', 'middleware' => ['admin'], 'as' => 'admin.'], function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Global Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    // Call Settings
    Route::get('call-settings', [CallSettingController::class, 'index'])->name('call-settings.index');
    Route::post('call-settings', [CallSettingController::class, 'update'])->name('call-settings.update');

    // API Settings
    Route::get('api-settings', [ApiSettingController::class, 'index'])->name('api-settings.index');
    Route::post('api-settings', [ApiSettingController::class, 'update'])->name('api-settings.update');
    Route::post('api-settings/test-connection', [ApiSettingController::class, 'testConnection'])->name('api-settings.test');

    // Leads & Enquiries
    Route::get('leads/export/csv', [LeadController::class, 'exportCsv'])->name('leads.export');
    Route::resource('leads', LeadController::class)->only(['index', 'show', 'update', 'destroy']);

    Route::get('enquiries/export/csv', [FlightEnquiryController::class, 'exportCsv'])->name('enquiries.export');
    Route::resource('enquiries', FlightEnquiryController::class)->only(['index', 'show', 'update', 'destroy']);

    // Communications
    Route::post('contacts/{contact}/reply', [ContactMessageController::class, 'reply'])->name('contacts.reply');
    Route::resource('contacts', ContactMessageController::class)->only(['index', 'show', 'update', 'destroy']);
    
    Route::get('newsletter/export/csv', [NewsletterController::class, 'exportCsv'])->name('newsletter.export');
    Route::post('newsletter/{subscriber}/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
    Route::resource('newsletter', NewsletterController::class)->only(['index', 'destroy']);

    // CMS Content
    Route::resource('offers', OfferController::class);
    Route::resource('blogs', BlogController::class);
    Route::resource('destinations', DestinationController::class);
    Route::resource('pages', PageController::class);
    Route::resource('faqs', FaqController::class);
    Route::resource('testimonials', TestimonialController::class);

    // SEO Settings
    Route::get('seo', [SeoController::class, 'index'])->name('seo.index');
    Route::get('seo/{seo}/edit', [SeoController::class, 'edit'])->name('seo.edit');
    Route::put('seo/{seo}', [SeoController::class, 'update'])->name('seo.update');

    // Schema Settings
    Route::resource('schema', SchemaController::class);

    // Users Management
    Route::resource('users', UserController::class);
});
