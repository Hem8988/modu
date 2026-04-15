<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadSubmissionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\InstallationController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MasterAttributeController;

// ── Public ──────────────────────────────────────────────────────────────────
Route::get('/',          [HomeController::class, 'index'])->name('home');
Route::get('/thank-you', [HomeController::class, 'thankYou'])->name('thank-you');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::post('/submit-lead', [LeadSubmissionController::class, 'submit'])->name('submit-lead');
Route::post('/twilio/reply', [App\Http\Controllers\TwilioWebhookController::class, 'handleReply'])->name('twilio.reply');


// Public quote view
Route::get('/quote/view/{token}',    [QuotationController::class, 'clientView'])->name('quote.client');
Route::post('/quote/accept/{token}', [QuotationController::class, 'clientAccept'])->name('quote.accept');

// ── Breeze Auth Routes ──────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// Breeze profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Redirect /dashboard to admin dashboard
Route::get('/dashboard', fn() => redirect()->route('admin.dashboard'))->middleware(['auth'])->name('dashboard');

// ── Admin (protected) ──────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile',   fn() => view('admin.profile', ['user' => auth()->user()]))->name('profile');

    // Leads
    Route::get('/leads/pipeline',         [LeadController::class, 'pipeline'])->name('leads.pipeline');
    Route::get('/leads',                  [LeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/{id}',             [LeadController::class, 'show'])->name('leads.show');
    Route::post('/leads',                 [LeadController::class, 'store'])->name('leads.store');
    Route::put('/leads/{id}',             [LeadController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{id}',          [LeadController::class, 'destroy'])->name('leads.destroy');
    Route::get('/leads/{id}/advance',     [LeadController::class, 'advanceStatus'])->name('leads.advance');
    Route::post('/leads/{id}/sms',        [LeadController::class, 'sendSms'])->name('leads.sms');
    Route::post('/leads/{id}/action',     [LeadController::class, 'addAction'])->name('leads.action');
    Route::put('/leads/{leadId}/action/{type}/{id}/update', [LeadController::class, 'updateAction'])->name('leads.action-update');
    Route::get('/leads/{leadId}/action/{type}/{id}/update', function ($leadId) { return redirect()->route('admin.leads.show', $leadId); });
    Route::get('/leads/{id}/logs',        [LeadController::class, 'logs'])->name('leads.logs');

    // Enquiries
    Route::get('/enquiries',              [EnquiryController::class, 'index'])->name('enquiries.index');
    Route::post('/enquiries',             [EnquiryController::class, 'store'])->name('enquiries.store');
    Route::put('/enquiries/{id}',         [EnquiryController::class, 'update'])->name('enquiries.update');
    Route::get('/enquiries/{id}/convert', [EnquiryController::class, 'convert'])->name('enquiries.convert');
    Route::get('/enquiries/{id}/spam',    [EnquiryController::class, 'spam'])->name('enquiries.spam');
    Route::delete('/enquiries/{id}',      [EnquiryController::class, 'destroy'])->name('enquiries.destroy');

    // Appointments
    Route::get('/appointments',           [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments',          [AppointmentController::class, 'store'])->name('appointments.store');
    Route::post('/appointments/{id}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::delete('/appointments/{id}',   [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::resource('installations', InstallationController::class);
    Route::resource('complaints', ComplaintController::class);
    Route::resource('quotations', QuotationController::class);

    // Quotations
    Route::get('/quotations/builder/{leadId}', [QuotationController::class, 'builder'])->name('quotations.builder');
    Route::post('/quotations/save/{leadId}',   [QuotationController::class, 'save'])->name('quotations.save');
    Route::get('/quotations/view/{id}',   [QuotationController::class, 'show'])->name('quotations.show');
    Route::get('/quotations/{id}/convert', [QuotationController::class, 'convertToInvoice'])->name('quotations.convert');
    Route::put('/quotations/{id}/status',  [QuotationController::class, 'updateStatus'])->name('quotations.status-update');
    Route::delete('/quotations/{id}',     [QuotationController::class, 'destroy'])->name('quotations.destroy');

    // Invoices
    Route::get('/invoices',               [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}',          [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices',              [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{id}/print',    [InvoiceController::class, 'print'])->name('invoices.print');
    Route::put('/invoices/{id}/status',   [InvoiceController::class, 'updateStatus'])->name('invoices.status-update');
    Route::delete('/invoices/{id}',       [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    // Payments
    Route::get('/payments',               [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments',              [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{id}/print',    [PaymentController::class, 'print'])->name('payments.print');
    Route::delete('/payments/{id}',       [PaymentController::class, 'destroy'])->name('payments.destroy');

    // Products
    Route::get('/products',               [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',        [ProductController::class, 'create'])->name('products.create');
    Route::get('/products/{id}/edit',     [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/products',              [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{id}',          [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}',       [ProductController::class, 'destroy'])->name('products.destroy');

    // Feedback & Resolutions Command Hub
    Route::get('/complaints',                     [ComplaintController::class, 'index'])->name('complaints.index');
    Route::post('/complaints',                    [ComplaintController::class, 'store'])->name('complaints.store');
    Route::post('/complaints/feedback',           [ComplaintController::class, 'storeFeedback'])->name('complaints.feedback.store');
    Route::post('/complaints/service-request',    [ComplaintController::class, 'storeServiceRequest'])->name('complaints.service.store');
    Route::post('/complaints/resolution',         [ComplaintController::class, 'storeResolution'])->name('complaints.resolution.store');
    Route::put('/complaints/{id}',                [ComplaintController::class, 'update'])->name('complaints.update');
    Route::delete('/complaints/{id}',             [ComplaintController::class, 'destroy'])->name('complaints.destroy');

    // Global Settings
    Route::get('/settings',                       [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');

    // Master Attributes
    Route::post('/master-attributes', [MasterAttributeController::class, 'store'])->name('master-attributes.store');
    Route::put('/master-attributes/{id}', [MasterAttributeController::class, 'update'])->name('master-attributes.update');
    Route::delete('/master-attributes/{id}', [MasterAttributeController::class, 'destroy'])->name('master-attributes.destroy');
});
