<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\ToolsController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\DeliveryChargeController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\Admin\WebsiteController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AffiliateController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\MarketingController;
use App\Http\Controllers\Admin\AttrvaluesController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\AffiliateLinkController;
use App\Http\Controllers\Admin\EmailCampaignController;
use App\Http\Controllers\Admin\AffiliateClickController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\WithdrawMethodController;
use App\Http\Controllers\Admin\AffiliateReportController;
use App\Http\Controllers\Admin\ModalManagementController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminTransReceiveController;
use App\Http\Controllers\Admin\CommissionSettingController;
use App\Http\Controllers\Admin\PaymentInstructionController;
use App\Http\Controllers\Admin\AffiliateCommissionController;
use App\Http\Controllers\Admin\ProductSpecificationController;
use App\Http\Controllers\Admin\AdminPackageLinkSharingController;
use App\Http\Controllers\Admin\SupportController as AdminSupportController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which contains the "admin" middleware group.
|
*/
Route::get('/admin', function () {
    return redirect()->route('admin.login');
})->name('admin.index');

// Admin Authentication Routes (Guest only)
Route::prefix('admin')->name('admin.')->middleware('guest:admin')->group(function () {
    // Login Routes
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    // Password Reset Routes  
    Route::get('/forgot-password', [AdminAuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [AdminAuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AdminAuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AdminAuthController::class, 'reset'])->name('password.update');
});

// Public API routes (no authentication required) - outside admin prefix to avoid conflicts
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/categories/validate-slug', [CategoryController::class, 'validateSlug'])->name('categories.validate-slug');
    Route::get('/categories/parent-categories', [CategoryController::class, 'getParentCategories'])->name('categories.parent-categories');
    Route::get('/categories/{id}/details', [CategoryController::class, 'getCategoryDetails'])->name('categories.details');
});

// Mock routes for dashboard stats
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/stats/realtime', function() {
        return response()->json([
            'success' => true,
            'data' => [
                'orders' => ['count' => 42, 'change' => '+12%'],
                'sales' => ['amount' => 15420.50, 'change' => '+8.5%'],
                'visitors' => ['count' => 1284, 'change' => '+5.2%'],
                'bounce_rate' => ['rate' => '32.1%', 'change' => '-2.1%']
            ]
        ]);
    })->name('stats.realtime');
});

// Public API routes (no authentication required)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/categories/validate-slug', [CategoryController::class, 'validateSlug'])->name('categories.validate-slug-public');
    Route::get('/categories/api/parent-categories', [CategoryController::class, 'getParentCategories'])->name('categories.parent-categories-public');
    
    // Test route to verify routing works
    Route::get('/test', function() {
        return response()->json(['success' => true, 'message' => 'Test route works']);
    })->name('test');
    
    // Logout Route (accessible without middleware to handle logout properly)
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Admin Notices Management Routes
    Route::controller(App\Http\Controllers\Admin\AdminNoticeController::class)->prefix('notices')->name('notices.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'getData')->name('data'); // AJAX data endpoint
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('/{id}/toggle-status', 'toggleStatus')->name('toggle-status');
        Route::post('/bulk-delete', 'bulkDelete')->name('bulk-delete');
    });
    
    // Plans/Packages Management Routes
    Route::controller(App\Http\Controllers\Admin\PlanController::class)->prefix('plans')->name('plans.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{plan}', 'show')->name('show');
        Route::get('/{plan}/edit', 'edit')->name('edit');
        Route::put('/{plan}', 'update')->name('update');
        Route::delete('/{plan}', 'destroy')->name('destroy');
        Route::post('/{plan}/toggle-status', 'toggleStatus')->name('toggle-status');
        Route::post('/{plan}/toggle-featured', 'toggleFeatured')->name('toggle-featured');
    });
    // Transaction Receipts Management
    Route::prefix('trans-receipts')->name('trans-receipts.')->group(function () {
        Route::get('/', [AdminTransReceiveController::class, 'index'])->name('index');
        Route::get('/create', [AdminTransReceiveController::class, 'create'])->name('create');
        Route::post('/', [AdminTransReceiveController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminTransReceiveController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminTransReceiveController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminTransReceiveController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminTransReceiveController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/verify', [AdminTransReceiveController::class, 'verify'])->name('verify');
        Route::post('/{id}/change-status', [AdminTransReceiveController::class, 'changeStatus'])->name('change-status');
        Route::post('/bulk-action', [AdminTransReceiveController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [AdminTransReceiveController::class, 'export'])->name('export');
        Route::get('/{id}/generate-pdf', [AdminTransReceiveController::class, 'generatePdf'])->name('generate-pdf');
        Route::get('/dashboard/analytics', [AdminTransReceiveController::class, 'dashboard'])->name('dashboard');
    });

    // Payment Methods Management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::get('/{id}', [PaymentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PaymentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PaymentController::class, 'update'])->name('update');
        Route::delete('/{id}', [PaymentController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [PaymentController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{id}/set-default', [PaymentController::class, 'setDefault'])->name('set-default');
        Route::post('/{id}/test-connection', [PaymentController::class, 'testConnection'])->name('test-connection');
        Route::post('/bulk-action', [PaymentController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [PaymentController::class, 'export'])->name('export');
        Route::get('/analytics', [PaymentController::class, 'analytics'])->name('analytics');
    });
     // Modal Management Routes
    Route::controller(ModalManagementController::class)->group(function () {
        Route::get('/modals', 'index')->name('modal.index');
        Route::get('/modals/create', 'create')->name('modal.create');
        Route::post('/modals', 'store')->name('modal.store');
        Route::get('/modals/{id}', 'show')->name('modal.show');
        Route::get('/modals/{id}/edit', 'edit')->name('modal.edit'); 
        Route::put('/modals/{id}', 'update')->name('modal.update');
        Route::delete('/modals/{id}', 'destroy')->name('modal.destroy');
        Route::post('/modals/{id}/toggle-status', 'toggleStatus')->name('modal.toggle-status');
        Route::get('/modals/analytics', 'analytics')->name('modal.analytics');
        Route::post('/modals/bulk-action', 'bulkAction')->name('modal.bulk-action');
        Route::get('/modals/quick-stats', 'quickStats')->name('modal.quick-stats');
    });
    // Email Campaign Management Routes
    Route::controller(EmailCampaignController::class)->group(function () {
        Route::get('/email-campaigns', 'index')->name('email-campaigns.index');
        Route::get('/email-campaigns/analytics', 'analytics')->name('email-campaigns.analytics');
        Route::get('/email-campaigns/templates', 'templates')->name('email-campaigns.templates');
        Route::get('/email-campaigns/queue', 'queue')->name('email-campaigns.queue');
        Route::get('/email-campaigns/settings', 'settings')->name('email-campaigns.settings');

        // Campaign Actions
        Route::post('/email-campaigns/send-kyc-reminders', 'sendKycReminders')->name('email-campaigns.send-kyc-reminders');
        Route::post('/email-campaigns/send-inactive-reminders', 'sendInactiveReminders')->name('email-campaigns.send-inactive-reminders');
        Route::post('/email-campaigns/send-password-resets', 'sendPasswordResets')->name('email-campaigns.send-password-resets');
        Route::post('/email-campaigns/send-to-all-users', 'sendToAllUsers')->name('email-campaigns.send-to-all-users');

        // Queue Management
        Route::get('/email-campaigns/queue-status', 'queueStatus')->name('email-campaigns.queue-status');
        Route::post('/email-campaigns/retry-failed', 'retryFailed')->name('email-campaigns.retry-failed');
        Route::post('/email-campaigns/clear-failed', 'clearFailed')->name('email-campaigns.clear-failed');
        
        // Template Management
        Route::put('/email-campaigns/templates/{id}', 'updateTemplate')->name('email-campaigns.update-template');
        Route::get('/email-campaigns/templates/{slug}', 'getTemplate')->name('email-campaigns.get-template');

        // Command Execution
        Route::post('/email-campaigns/run-command', 'runCommand')->name('email-campaigns.run-command');
    });

    // Sub-Admin Management Routes
    Route::controller(App\Http\Controllers\Admin\SubAdminController::class)->middleware(['auth:admin'])->group(function () {
        Route::get('/sub-admins', 'index')->name('sub-admins.index');
        Route::get('/sub-admins/create', 'create')->name('sub-admins.create');
        Route::get('/sub-admins/permissions', 'permissions')->name('sub-admins.permissions');
        Route::post('/sub-admins', 'store')->name('sub-admins.store');
        Route::get('/sub-admins/{id}', 'show')->name('sub-admins.show');
        Route::get('/sub-admins/{id}/edit', 'edit')->name('sub-admins.edit');
        Route::put('/sub-admins/{id}', 'update')->name('sub-admins.update');
        Route::delete('/sub-admins/{id}', 'destroy')->name('sub-admins.destroy');
        Route::get('/sub-admins/{id}/toggle-status', 'toggleStatus')->name('sub-admins.toggle-status');
        Route::get('/sub-admins/{id}/reset-password', 'resetPassword')->name('sub-admins.reset-password');
    });

    // General Settings Routes
    Route::controller(App\Http\Controllers\Admin\GeneralSettingController::class)->group(function () {
        Route::get('/general-settings', 'index')->name('general-settings.index');
        Route::get('/general-settings/general', 'index')->name('general-settings.general');
        Route::put('/general-settings/general', 'update')->name('general-settings.update');
        Route::get('/general-settings/company-info', 'companyInfo')->name('general-settings.company-info');
        Route::post('/general-settings/company-info', 'updateCompanyInfo')->name('general-settings.company-info.update');
        Route::get('/general-settings/media', 'mediaSettings')->name('general-settings.media');
        Route::post('/general-settings/media', 'updateMediaSettings')->name('general-settings.media.update');
        Route::get('/general-settings/seo', 'seoSettings')->name('general-settings.seo');
        Route::post('/general-settings/seo', 'updateSeoSettings')->name('general-settings.seo.update');
        Route::get('/general-settings/content', 'contentSettings')->name('general-settings.content');
        Route::post('/general-settings/content', 'updateContentSettings')->name('general-settings.content.update');
        Route::get('/general-settings/theme', 'themeSettings')->name('general-settings.theme');
        Route::post('/general-settings/theme', 'updateThemeSettings')->name('general-settings.theme.update');
        Route::get('/general-settings/social-media', 'socialMediaSettings')->name('general-settings.social-media');
        Route::post('/general-settings/social-media', 'updateSocialMediaSettings')->name('general-settings.social-media.update');
        Route::get('/general-settings/mail-config', 'mailConfig')->name('general-settings.mail-config');
        Route::post('/general-settings/mail-config', 'updateMailConfig')->name('general-settings.mail-config.update');
        Route::get('/general-settings/sms-config', 'smsConfig')->name('general-settings.sms-config');
        Route::post('/general-settings/sms-config', 'updateSmsConfig')->name('general-settings.sms-config.update');
        Route::post('/general-settings/test-sms', 'testSms')->name('general-settings.test-sms');
        Route::post('/general-settings/check-sms-balance', 'checkSmsBalance')->name('general-settings.check-sms-balance');
        Route::post('/general-settings/diagnose-sms', 'diagnoseSms')->name('general-settings.diagnose-sms');
        Route::post('/general-settings/send-bulk-sms', 'sendBulkSms')->name('general-settings.send-bulk-sms');
        Route::post('/general-settings/send-many-to-many', 'sendManyToMany')->name('general-settings.send-many-to-many');
        Route::get('/general-settings/security', 'securitySettings')->name('general-settings.security');
        Route::post('/general-settings/security', 'updateSecuritySettings')->name('general-settings.security.update');
        Route::get('/general-settings/fee-settings', 'feeSettings')->name('general-settings.fee-settings');
        Route::post('/general-settings/fee-settings', 'updateFeeSettings')->name('general-settings.fee-settings.update');
        Route::post('/general-settings/clear-cache', 'clearCache')->name('general-settings.clear-cache');
        Route::post('/general-settings/toggle-maintenance', 'toggleMaintenanceMode')->name('general-settings.toggle-maintenance');
        Route::get('/general-settings/system-info', 'getSystemInfo')->name('general-settings.system-info');
        Route::post('/general-settings/test-email', 'testEmail')->name('general-settings.test-email');
        Route::get('/general-settings/mail-status', 'getMailConfigStatus')->name('general-settings.mail-status');
        Route::get('/general-settings/export', 'exportSettings')->name('general-settings.export');
        Route::post('/general-settings/import', 'importSettings')->name('general-settings.import');
    });
    // Payment Instructions Routes
    Route::controller(PaymentInstructionController::class)->group(function () {
        Route::get('/payment-instructions', 'index')->name('payment-instructions.index');
        Route::put('/payment-instructions', 'update')->name('payment-instructions.update');
        Route::post('/payment-instructions/preview', 'preview')->name('payment-instructions.preview');
        Route::get('/payment-instructions/{code}', 'getInstructions')->name('payment-instructions.get');
    });
    // Support Management Routes
    Route::controller(SupportController::class)->group(function () {
        Route::get('/support', 'index')->name('support.index');
        Route::get('/support/tickets', 'tickets')->name('support.tickets');
        Route::get('/support/tickets/{id}', 'show')->name('support.show');
        Route::post('/support/tickets/{id}/reply', 'reply')->name('support.reply');
        Route::post('/support/tickets/{id}/status', 'updateStatus')->name('support.update-status');
        Route::post('/support/tickets/{id}/star', 'toggleStar')->name('support.toggle-star');
        Route::post('/support/bulk-action', 'bulkAction')->name('support.bulk-action');
        Route::get('/support/export', 'export')->name('support.export');
    });
    Route::controller(App\Http\Controllers\Admin\AdminKycController::class)->group(function () {
            Route::get('/kyc', 'index')->name('kyc.index');
            Route::get('/kyc/pending', 'pending')->name('kyc.pending');
            Route::get('/kyc/approved', 'approved')->name('kyc.approved');
            Route::get('/kyc/rejected', 'rejected')->name('kyc.rejected');
            Route::get('/kyc/under-review', 'getUnderReview')->name('kyc.under-review');
            Route::get('/kyc/{id}', 'show')->name('kyc.show');
            Route::post('/kyc/{id}/update-status', 'updateStatus')->name('kyc.update-status');
            Route::post('/kyc/bulk-approve', 'bulkApprove')->name('kyc.bulk-approve');
            Route::post('/kyc/bulk-change-status', 'bulkChangeStatus')->name('kyc.bulk-change-status');
            Route::post('/kyc/{id}/mark-under-review', 'markUnderReview')->name('kyc.mark-under-review');
            Route::get('/kyc/{id}/document/view/{type}', 'viewDocument')->name('kyc.document.view');
            Route::get('/kyc/{id}/document/download/{type}', 'downloadDocument')->name('kyc.document.download');
            Route::get('/kyc/statistics/data', 'statistics')->name('kyc.statistics');
            
            // Enhanced KYC Routes
            Route::get('/kyc/export/csv', 'exportCsv')->name('kyc.export.csv');
            Route::get('/kyc/dashboard/stats', 'getDashboardStats')->name('kyc.dashboard.stats');
            Route::post('/kyc/{id}/assign-admin', 'assignToAdmin')->name('kyc.assign.admin');
            Route::post('/kyc/{id}/update-risk-level', 'updateRiskLevel')->name('kyc.update.risk.level');
            Route::get('/kyc/{id}/activity-log', 'getActivityLog')->name('kyc.activity.log');
            Route::get('/kyc/{id}/documents/download-all', 'downloadAllDocuments')->name('kyc.documents.download.all');
        });
        
    // Vendor KYC Routes
    Route::controller(App\Http\Controllers\Admin\AdminVendorKycController::class)->group(function () {
        Route::get('/vendor-kyc', 'index')->name('vendor-kyc.index');
        Route::get('/vendor-kyc/pending', 'pending')->name('vendor-kyc.pending');
        Route::get('/vendor-kyc/approved', 'approved')->name('vendor-kyc.approved');
        Route::get('/vendor-kyc/rejected', 'rejected')->name('vendor-kyc.rejected');
        Route::get('/vendor-kyc/under-review', 'getUnderReview')->name('vendor-kyc.under-review');
        Route::get('/vendor-kyc/{id}', 'show')->name('vendor-kyc.show');
        Route::post('/vendor-kyc/{id}/update-status', 'updateStatus')->name('vendor-kyc.update-status');
        Route::post('/vendor-kyc/bulk-approve', 'bulkApprove')->name('vendor-kyc.bulk-approve');
        Route::post('/vendor-kyc/bulk-change-status', 'bulkChangeStatus')->name('vendor-kyc.bulk-change-status');
        Route::post('/vendor-kyc/{id}/mark-under-review', 'markUnderReview')->name('vendor-kyc.mark-under-review');
        Route::get('/vendor-kyc/{id}/document/view/{type}', 'viewDocument')->name('vendor-kyc.document.view');
        Route::get('/vendor-kyc/{id}/document/download/{type}', 'downloadDocument')->name('vendor-kyc.document.download');
        Route::get('/vendor-kyc/statistics/data', 'statistics')->name('vendor-kyc.statistics');
    });
        
    // Transfer & Withdrawal Conditions Routes
    Route::controller(App\Http\Controllers\Admin\TransferWithdrawConditionsController::class)->group(function () {
        Route::get('/transfer-withdraw-conditions', 'index')->name('transfer-withdraw-conditions.index');
        Route::put('/transfer-withdraw-conditions', 'update')->name('transfer-withdraw-conditions.update');
        Route::post('/transfer-withdraw-conditions/reset', 'resetToDefaults')->name('transfer-withdraw-conditions.reset');
        Route::get('/transfer-withdraw-conditions/summary', 'getConditionsSummary')->name('transfer-withdraw-conditions.summary');
    });

    // Attributes Management
    Route::prefix('attributes')->name('attributes.')->group(function () {
        Route::get('/', [AttributeController::class, 'index'])->name('index');
        Route::get('/create', [AttributeController::class, 'create'])->name('create');
        
        // Specific attribute type routes - must come before dynamic {id} routes
        Route::get('/sizes', [AttributeController::class, 'sizes'])->name('sizes');
        Route::get('/colors', [AttributeController::class, 'colors'])->name('colors');
        Route::get('/materials', [AttributeController::class, 'materials'])->name('materials');
        Route::get('/brands', [AttributeController::class, 'brands'])->name('brands');
        Route::get('/sets', [AttributeController::class, 'sets'])->name('sets');
        Route::get('/groups', [AttributeController::class, 'groups'])->name('groups');
        Route::get('/bulk-import', [AttributeController::class, 'bulkImport'])->name('bulk-import');
        Route::get('/export/csv', [AttributeController::class, 'export'])->name('export');
        Route::get('/analytics', [AttributeController::class, 'analytics'])->name('analytics');
        
        // Dynamic routes with ID parameters
        Route::post('/', [AttributeController::class, 'store'])->name('store');
        Route::get('/{id}', [AttributeController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AttributeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AttributeController::class, 'update'])->name('update');
        Route::delete('/{id}', [AttributeController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [AttributeController::class, 'toggleStatus'])->name('toggle-status');
        Route::put('/{id}/ajax-update', [AttributeController::class, 'updateAjax'])->name('ajax-update');
        Route::delete('/{id}/ajax-delete', [AttributeController::class, 'deleteAjax'])->name('ajax-delete');
        Route::get('/{id}/values', [AttributeController::class, 'values'])->name('values');
        Route::post('/{id}/assign-categories', [AttributeController::class, 'assignCategories'])->name('assign-categories');
        
        // AJAX routes for database operations
        Route::put('/{id}/ajax-update', [AttributeController::class, 'updateAjax'])->name('ajax-update');
        Route::delete('/{id}/ajax-delete', [AttributeController::class, 'deleteAjax'])->name('ajax-delete');
        
        // Bulk operations
        Route::post('/bulk-action', [AttributeController::class, 'bulkAction'])->name('bulk-action');
        Route::post('/bulk-import', [AttributeController::class, 'processBulkImport'])->name('bulk-import.process');
    });

    // Attribute Values Management
    Route::prefix('attribute-values')->name('attribute-values.')->group(function () {
        Route::get('/', [AttrvaluesController::class, 'index'])->name('index');
        Route::get('/create', [AttrvaluesController::class, 'create'])->name('create');
        Route::post('/', [AttrvaluesController::class, 'store'])->name('store');
        Route::get('/{id}', [AttrvaluesController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AttrvaluesController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AttrvaluesController::class, 'update'])->name('update');
        Route::delete('/{id}', [AttrvaluesController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [AttrvaluesController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [AttrvaluesController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [AttrvaluesController::class, 'export'])->name('export');
        Route::get('/by-attribute/{attributeId}', [AttrvaluesController::class, 'getByAttribute'])->name('by-attribute');
        Route::post('/bulk-transfer', [AttrvaluesController::class, 'bulkTransfer'])->name('bulk-transfer');
    });

    // Categories & Subcategories Management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{id}/toggle-featured', [CategoryController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::post('/bulk-action', [CategoryController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/bulk-import', [CategoryController::class, 'bulkImport'])->name('bulk-import');
        Route::post('/bulk-import', [CategoryController::class, 'processBulkImport'])->name('bulk-import.process');
        Route::get('/export/csv', [CategoryController::class, 'export'])->name('export');
        Route::get('/tree', [CategoryController::class, 'treeView'])->name('tree');
        Route::get('/tree-view', [CategoryController::class, 'treeView'])->name('tree-view');
        Route::post('/reorder', [CategoryController::class, 'reorder'])->name('reorder');
        
        // AJAX endpoints
        Route::get('/validate-slug', [CategoryController::class, 'validateSlug'])->name('validate-slug');
        Route::get('/api/parent-categories', [CategoryController::class, 'getParentCategories'])->name('api.parent-categories');
        Route::get('/{id}/details', [CategoryController::class, 'getCategoryDetails'])->name('details');
    });

    // Subcategories Management
    Route::prefix('subcategories')->name('subcategories.')->group(function () {
        Route::get('/', [SubcategoryController::class, 'index'])->name('index');
        Route::get('/create', [SubcategoryController::class, 'create'])->name('create');
        Route::post('/', [SubcategoryController::class, 'store'])->name('store');
        Route::get('/{id}', [SubcategoryController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SubcategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SubcategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [SubcategoryController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [SubcategoryController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{id}/toggle-featured', [SubcategoryController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::post('/{id}/duplicate', [SubcategoryController::class, 'duplicate'])->name('duplicate');
        Route::post('/bulk-action', [SubcategoryController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/bulk-import', [SubcategoryController::class, 'bulkImport'])->name('bulk-import');
        Route::post('/bulk-import', [SubcategoryController::class, 'processBulkImport'])->name('bulk-import.process');
        Route::get('/export/csv', [SubcategoryController::class, 'export'])->name('export');
        Route::get('/by-category/{categoryId}', [SubcategoryController::class, 'getByCategory'])->name('by-category');
        Route::post('/reorder', [SubcategoryController::class, 'reorder'])->name('reorder');
        
        // AJAX endpoints for dynamic content
        Route::get('/search', [SubcategoryController::class, 'search'])->name('search');
        Route::get('/filter', [SubcategoryController::class, 'filter'])->name('filter');
        Route::post('/bulk-status-update', [SubcategoryController::class, 'bulkStatusUpdate'])->name('bulk-status-update');
        Route::post('/bulk-featured-update', [SubcategoryController::class, 'bulkFeaturedUpdate'])->name('bulk-featured-update');
    });

    // Brands Management
    Route::prefix('brands')->name('brands.')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('index');
        Route::get('/create', [BrandController::class, 'create'])->name('create');
        Route::post('/', [BrandController::class, 'store'])->name('store');
        Route::get('/validate-slug', [BrandController::class, 'validateSlug'])->name('validate-slug');
        Route::get('/{id}', [BrandController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [BrandController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BrandController::class, 'update'])->name('update');
        Route::delete('/{id}', [BrandController::class, 'destroy'])->name('destroy');
        Route::get('/featured', [BrandController::class, 'featured'])->name('featured');
        Route::post('/{id}/toggle-status', [BrandController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{id}/toggle-featured', [BrandController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::post('/bulk-action', [BrandController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/bulk-import', [BrandController::class, 'bulkImport'])->name('bulk-import');
        Route::post('/bulk-import', [BrandController::class, 'processBulkImport'])->name('bulk-import.process');
        Route::get('/export/csv', [BrandController::class, 'export'])->name('export');
        Route::get('/analytics', [BrandController::class, 'analytics'])->name('analytics');
        Route::post('/reorder', [BrandController::class, 'reorder'])->name('reorder');
    });

    // Colors Management
    Route::prefix('colors')->name('colors.')->group(function () {
        Route::get('/', [ColorController::class, 'index'])->name('index');
        Route::get('/create', [ColorController::class, 'create'])->name('create');
        Route::post('/', [ColorController::class, 'store'])->name('store');
        Route::get('/{id}', [ColorController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ColorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ColorController::class, 'update'])->name('update');
        Route::delete('/{id}', [ColorController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [ColorController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [ColorController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/bulk-import', [ColorController::class, 'bulkImport'])->name('bulk-import');
        Route::post('/bulk-import', [ColorController::class, 'processBulkImport'])->name('bulk-import.process');
        Route::get('/export/csv', [ColorController::class, 'export'])->name('export');
        Route::get('/analytics', [ColorController::class, 'analytics'])->name('analytics');
    });

    // Sizes Management
    Route::prefix('sizes')->name('sizes.')->group(function () {
        Route::get('/', [SizeController::class, 'index'])->name('index');
        Route::get('/create', [SizeController::class, 'create'])->name('create');
        Route::post('/', [SizeController::class, 'store'])->name('store');
        Route::get('/{id}', [SizeController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SizeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SizeController::class, 'update'])->name('update');
        Route::delete('/{id}', [SizeController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [SizeController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [SizeController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/bulk-import', [SizeController::class, 'bulkImport'])->name('bulk-import');
        Route::post('/bulk-import', [SizeController::class, 'processBulkImport'])->name('bulk-import.process');
        Route::get('/export/csv', [SizeController::class, 'export'])->name('export');
        Route::get('/analytics', [SizeController::class, 'analytics'])->name('analytics');
    });

    // Variants Management
    Route::prefix('variants')->name('variants.')->group(function () {
        Route::get('/', [VariantController::class, 'index'])->name('index');
        Route::get('/create', [VariantController::class, 'create'])->name('create');
        Route::post('/', [VariantController::class, 'store'])->name('store');
        Route::get('/{id}', [VariantController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [VariantController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VariantController::class, 'update'])->name('update');
        Route::delete('/{id}', [VariantController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [VariantController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [VariantController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/bulk-import', [VariantController::class, 'bulkImport'])->name('bulk-import');
        Route::post('/bulk-import', [VariantController::class, 'processBulkImport'])->name('bulk-import.process');
        Route::get('/export/csv', [VariantController::class, 'export'])->name('export');
        Route::get('/analytics', [VariantController::class, 'analytics'])->name('analytics');
    });

    // Banner Management
    Route::prefix('banners')->name('banners.')->group(function () {
        Route::get('/', [BannerController::class, 'index'])->name('index');
        Route::get('/create', [BannerController::class, 'create'])->name('create');
        Route::post('/', [BannerController::class, 'store'])->name('store');
        Route::get('/{id}', [BannerController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [BannerController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BannerController::class, 'update'])->name('update');
        Route::delete('/{id}', [BannerController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [BannerController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [BannerController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [BannerController::class, 'export'])->name('export');
        Route::get('/analytics', [BannerController::class, 'analytics'])->name('analytics');
        Route::post('/{id}/track-impression', [BannerController::class, 'trackImpression'])->name('track-impression');
        Route::post('/{id}/track-click', [BannerController::class, 'trackClick'])->name('track-click');
        Route::post('/reorder', [BannerController::class, 'reorder'])->name('reorder');
    });

    // Popup Management
    Route::prefix('popups')->name('popups.')->group(function () {
        Route::get('/', [PopupController::class, 'index'])->name('index');
        Route::get('/create', [PopupController::class, 'create'])->name('create');
        Route::post('/', [PopupController::class, 'store'])->name('store');
        Route::get('/{id}', [PopupController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PopupController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PopupController::class, 'update'])->name('update');
        Route::delete('/{id}', [PopupController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/duplicate', [PopupController::class, 'duplicate'])->name('duplicate');
        Route::post('/{id}/toggle-status', [PopupController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [PopupController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [PopupController::class, 'export'])->name('export');
        Route::get('/analytics', [PopupController::class, 'analytics'])->name('analytics');
        Route::post('/{id}/track-show', [PopupController::class, 'trackShow'])->name('track-show');
        Route::post('/{id}/track-click', [PopupController::class, 'trackClick'])->name('track-click');
        Route::post('/{id}/track-close', [PopupController::class, 'trackClose'])->name('track-close');
        Route::get('/{id}/preview', [PopupController::class, 'preview'])->name('preview');
    });

    // Withdraw Methods Management
    Route::prefix('withdraw-methods')->name('withdraw-methods.')->group(function () {
        Route::get('/', [WithdrawMethodController::class, 'index'])->name('index');
        Route::get('/create', [WithdrawMethodController::class, 'create'])->name('create');
        Route::post('/', [WithdrawMethodController::class, 'store'])->name('store');
        Route::get('/{id}', [WithdrawMethodController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [WithdrawMethodController::class, 'edit'])->name('edit');
        Route::put('/{id}', [WithdrawMethodController::class, 'update'])->name('update');
        Route::delete('/{id}', [WithdrawMethodController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [WithdrawMethodController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{id}/test-gateway', [WithdrawMethodController::class, 'testGateway'])->name('test-gateway');
        Route::post('/bulk-action', [WithdrawMethodController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [WithdrawMethodController::class, 'export'])->name('export');
        Route::get('/analytics', [WithdrawMethodController::class, 'analytics'])->name('analytics');
        Route::post('/{id}/calculate-charges', [WithdrawMethodController::class, 'calculateCharges'])->name('calculate-charges');
    });

    // Coupon Management
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::get('/create', [CouponController::class, 'create'])->name('create');
        Route::post('/', [CouponController::class, 'store'])->name('store');
        Route::get('/{id}', [CouponController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CouponController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CouponController::class, 'update'])->name('update');
        Route::delete('/{id}', [CouponController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [CouponController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/generate-code', [CouponController::class, 'generateCode'])->name('generate-code');
        Route::post('/validate-code', [CouponController::class, 'validateCode'])->name('validate-code');
        Route::get('/export', [CouponController::class, 'export'])->name('export');
        Route::get('/analytics', [CouponController::class, 'analytics'])->name('analytics');
    });

    // Financial Management
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'dashboard'])->name('dashboard');
        Route::get('/transactions', [FinanceController::class, 'transactions'])->name('transactions');
        Route::get('/payments', function () { return view('admin.finance.payments'); })->name('payments');
        Route::get('/refunds', function () { return view('admin.finance.refunds'); })->name('refunds');
        Route::get('/wallets', [FinanceController::class, 'wallets'])->name('wallets');
        Route::get('/withdrawals', [FinanceController::class, 'withdrawals'])->name('withdrawals');
        Route::post('/withdrawals/{id}/approve', [FinanceController::class, 'approveWithdrawal'])->name('withdrawals.approve');
        Route::post('/withdrawals/{id}/reject', [FinanceController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
        Route::post('/withdrawals/{id}/process', [FinanceController::class, 'processWithdrawal'])->name('withdrawals.process');
        Route::post('/withdrawals/bulk-approve', [FinanceController::class, 'bulkApproveWithdrawals'])->name('withdrawals.bulk-approve');
        Route::get('/withdrawals/{id}/details', [FinanceController::class, 'getWithdrawalDetails'])->name('withdrawals.details');
        
        // Withdrawal management routes
        Route::post('/withdrawals/{id}/approve', [FinanceController::class, 'approveWithdrawal'])->name('withdrawals.approve');
        Route::post('/withdrawals/{id}/reject', [FinanceController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
        Route::post('/withdrawals/{id}/process', [FinanceController::class, 'processWithdrawal'])->name('withdrawals.process');
        Route::post('/withdrawals/bulk-approve', [FinanceController::class, 'bulkApproveWithdrawals'])->name('withdrawals.bulk-approve');
        
        // Deposits management
        Route::get('/deposits', [FinanceController::class, 'deposits'])->name('deposits');
        Route::get('/deposits/{id}', [FinanceController::class, 'showDeposit'])->name('deposits.show');
        Route::post('/deposits/{id}/approve', [FinanceController::class, 'approveDeposit'])->name('deposits.approve');
        Route::post('/deposits/{id}/reject', [FinanceController::class, 'rejectDeposit'])->name('deposits.reject');
        Route::post('/deposits/bulk-approve', [FinanceController::class, 'bulkApproveDeposits'])->name('deposits.bulk-approve');
        Route::post('/deposits/bulk-reject', [FinanceController::class, 'bulkRejectDeposits'])->name('deposits.bulk-reject');
        
        // Admin Balance Transfer
        Route::get('/transfer', [FinanceController::class, 'showTransferForm'])->name('transfer');
        Route::post('/transfer/search-users', [FinanceController::class, 'searchUsers'])->name('transfer.search-users');
        Route::post('/transfer/execute', [FinanceController::class, 'transferBalance'])->name('transfer.execute');
        Route::get('/transfer/history', [FinanceController::class, 'transferHistory'])->name('transfer.history');
        Route::get('/transfer/details/{id}', [FinanceController::class, 'getTransferDetails'])->name('transfer.details');
        Route::get('/transfer/export', [FinanceController::class, 'exportTransfers'])->name('transfer.export');
        
        Route::get('/tax-management', function () { return view('admin.finance.tax'); })->name('tax');
        Route::get('/reporting', function () { return view('admin.finance.reporting'); })->name('reporting');
        Route::get('/accounting', function () { return view('admin.finance.accounting'); })->name('accounting');
        Route::post('/transactions/filter', function () { return redirect()->back(); })->name('transactions.filter');
        Route::patch('/refunds/{id}/process', function ($id) { return redirect()->back(); })->name('refunds.process');
    });

    // Tax Management
    Route::prefix('taxes')->name('taxes.')->group(function () {
        Route::get('/', [TaxController::class, 'index'])->name('index');
        Route::get('/create', [TaxController::class, 'create'])->name('create');
        Route::post('/', [TaxController::class, 'store'])->name('store');
        Route::get('/{id}', [TaxController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [TaxController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TaxController::class, 'update'])->name('update');
        Route::delete('/{id}', [TaxController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [TaxController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [TaxController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [TaxController::class, 'export'])->name('export');
    });

    // Settings Management
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('/general', [SettingsController::class, 'general'])->name('general');
        Route::post('/general', [SettingsController::class, 'updateGeneral'])->name('general.update');
        Route::get('/payment', [SettingsController::class, 'payment'])->name('payment');
        Route::post('/payment', [SettingsController::class, 'updatePayment'])->name('payment.update');
        Route::get('/shipping', [SettingsController::class, 'shipping'])->name('shipping');
        Route::post('/shipping', [SettingsController::class, 'updateShipping'])->name('shipping.update');
        Route::get('/tax', [SettingsController::class, 'tax'])->name('tax');
        Route::post('/tax', [SettingsController::class, 'updateTax'])->name('tax.update');
        Route::get('/email', [SettingsController::class, 'email'])->name('email');
        Route::post('/email', [SettingsController::class, 'updateEmail'])->name('email.update');
        Route::get('/mlm', [SettingsController::class, 'mlm'])->name('mlm');
        Route::post('/mlm', [SettingsController::class, 'updateMlm'])->name('mlm.update');
        Route::get('/currencies', [SettingsController::class, 'currencies'])->name('currencies');
        Route::post('/currencies', [SettingsController::class, 'updateCurrencies'])->name('currencies.update');
        Route::get('/localization', [SettingsController::class, 'localization'])->name('localization');
        Route::post('/localization', [SettingsController::class, 'updateLocalization'])->name('localization.update');
        Route::get('/integrations', [SettingsController::class, 'integrations'])->name('integrations');
        Route::post('/integrations', [SettingsController::class, 'updateIntegrations'])->name('integrations.update');
    });

    // System Tools
    Route::prefix('tools')->name('tools.')->group(function () {
        // Cache Management
        Route::get('/cache', [ToolsController::class, 'cache'])->name('cache');
        Route::post('/cache/clear', [ToolsController::class, 'clearCache'])->name('cache.clear');
        Route::get('/cache/info', [ToolsController::class, 'getCacheInfo'])->name('cache.info');
        
        // System Logs
        Route::get('/logs', [ToolsController::class, 'logs'])->name('logs');
        Route::get('/logs/data', [ToolsController::class, 'getLogData'])->name('logs.data');
        Route::post('/logs/clear', [ToolsController::class, 'clearLogs'])->name('logs.clear');
        Route::get('/logs/download', [ToolsController::class, 'downloadLogs'])->name('logs.download');
        
        // Database Backup
        Route::get('/backup', [ToolsController::class, 'backup'])->name('backup');
        Route::post('/backup/create', [ToolsController::class, 'createBackup'])->name('backup.create');
        Route::get('/backup/list', [ToolsController::class, 'getBackups'])->name('backup.list');
        Route::get('/backup/stats', [ToolsController::class, 'getBackupStats'])->name('backup.stats');
        Route::get('/backup/download/{filename}', [ToolsController::class, 'downloadBackup'])->name('backup.download');
        Route::delete('/backup/delete/{filename}', [ToolsController::class, 'deleteBackup'])->name('backup.delete');
        
        // System Maintenance
        Route::get('/maintenance', [ToolsController::class, 'maintenance'])->name('maintenance');
        Route::post('/maintenance/toggle', [ToolsController::class, 'toggleMaintenance'])->name('maintenance.toggle');
        Route::post('/maintenance/optimize', [ToolsController::class, 'optimizeSystem'])->name('maintenance.optimize');
        Route::get('/maintenance/health', [ToolsController::class, 'getSystemHealth'])->name('maintenance.health');
        
        // Task Management
        Route::post('/task/run', [ToolsController::class, 'runTask'])->name('task.run');
        Route::post('/task/pause', [ToolsController::class, 'pauseTask'])->name('task.pause');
        Route::post('/task/resume', [ToolsController::class, 'resumeTask'])->name('task.resume');
        
        // Data Import/Export
        Route::get('/imports', [ToolsController::class, 'imports'])->name('imports');
        Route::post('/imports/import', [ToolsController::class, 'importData'])->name('imports.import');
        Route::post('/imports/export', [ToolsController::class, 'exportData'])->name('imports.export');
        Route::get('/imports/history', [ToolsController::class, 'getImportExportHistory'])->name('imports.history');
        Route::get('/export/download/{filename}', [ToolsController::class, 'downloadExport'])->name('export.download');
    });

    // Commission Management
    Route::prefix('commissions')->name('commissions.')->group(function () {
        Route::get('/overview', [CommissionController::class, 'overview'])->name('overview');
        Route::get('/direct', [CommissionController::class, 'direct'])->name('direct');
        Route::get('/binary', [CommissionController::class, 'binary'])->name('binary');
        Route::get('/matching', [CommissionController::class, 'matching'])->name('matching');
        Route::get('/leadership', [CommissionController::class, 'leadership'])->name('leadership');
        Route::get('/payouts', [CommissionController::class, 'payouts'])->name('payouts');
        Route::put('/update-status/{commission}', [CommissionController::class, 'updateStatus'])->name('update-status');
        Route::post('/bulk-update-status', [CommissionController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
        Route::get('/export', [CommissionController::class, 'export'])->name('export');
    });

    // Commission Settings Management
    Route::prefix('commission-settings')->name('commission-settings.')->group(function () {
        Route::get('/', [CommissionSettingController::class, 'index'])->name('index');
        Route::get('/create', [CommissionSettingController::class, 'create'])->name('create');
        Route::post('/', [CommissionSettingController::class, 'store'])->name('store');
        Route::get('/{commissionSetting}', [CommissionSettingController::class, 'show'])->name('show');
        Route::get('/{commissionSetting}/edit', [CommissionSettingController::class, 'edit'])->name('edit');
        Route::put('/{commissionSetting}', [CommissionSettingController::class, 'update'])->name('update');
        Route::delete('/{commissionSetting}', [CommissionSettingController::class, 'destroy'])->name('destroy');
        Route::post('/{commissionSetting}/toggle-status', [CommissionSettingController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Affiliate Management
    Route::prefix('affiliates')->name('affiliates.')->group(function () {
        Route::get('/', [AffiliateController::class, 'index'])->name('index');
        Route::get('/create', [AffiliateController::class, 'create'])->name('create');
        Route::post('/', [AffiliateController::class, 'store'])->name('store');
        Route::get('/{affiliate}', [AffiliateController::class, 'show'])->name('show');
        Route::get('/{affiliate}/edit', [AffiliateController::class, 'edit'])->name('edit');
        Route::put('/{affiliate}', [AffiliateController::class, 'update'])->name('update');
        Route::delete('/{affiliate}', [AffiliateController::class, 'destroy'])->name('destroy');
        Route::post('/{affiliate}/update-status', [AffiliateController::class, 'updateStatus'])->name('update-status');
        Route::get('/export/csv', [AffiliateController::class, 'export'])->name('export');
        Route::get('/analytics/data', [AffiliateController::class, 'analytics'])->name('analytics');
    });

    // Affiliate Click Tracking
    Route::prefix('affiliate-clicks')->name('affiliate-clicks.')->group(function () {
        Route::get('/', [AffiliateClickController::class, 'index'])->name('index');
        Route::get('/{click}', [AffiliateClickController::class, 'show'])->name('show');
        Route::delete('/{click}', [AffiliateClickController::class, 'destroy'])->name('destroy');
        Route::get('/analytics/data', [AffiliateClickController::class, 'analytics'])->name('analytics');
        Route::get('/export/csv', [AffiliateClickController::class, 'export'])->name('export');
        Route::post('/bulk-delete', [AffiliateClickController::class, 'bulkDelete'])->name('bulk-delete');
    });

    // Affiliate Commissions
    Route::prefix('affiliate-commissions')->name('affiliate-commissions.')->group(function () {
        Route::get('/', [AffiliateCommissionController::class, 'index'])->name('index');
        Route::get('/{commission}', [AffiliateCommissionController::class, 'show'])->name('show');
        Route::post('/{commission}/update-status', [AffiliateCommissionController::class, 'updateStatus'])->name('update-status');
        Route::post('/bulk-update-status', [AffiliateCommissionController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
        Route::get('/analytics/data', [AffiliateCommissionController::class, 'analytics'])->name('analytics');
        Route::get('/export/csv', [AffiliateCommissionController::class, 'export'])->name('export');
        Route::get('/payout/preview', [AffiliateCommissionController::class, 'payoutPreview'])->name('payout.preview');
        Route::post('/payout/process', [AffiliateCommissionController::class, 'processPayout'])->name('payout.process');
    });

    // Affiliate Shared Links
    Route::prefix('affiliate-links')->name('affiliate-links.')->group(function () {
        Route::get('/', [AffiliateLinkController::class, 'index'])->name('index');
        Route::get('/analytics', [AffiliateLinkController::class, 'analytics'])->name('analytics');
        Route::get('/export/csv', [AffiliateLinkController::class, 'export'])->name('export');
        Route::get('/performance/top-products', [AffiliateLinkController::class, 'topProducts'])->name('top-products');
        Route::get('/performance/top-affiliates', [AffiliateLinkController::class, 'topAffiliates'])->name('top-affiliates');
        Route::get('/{product}', [AffiliateLinkController::class, 'show'])->name('show');
    });

    // Affiliate Performance Reports
    Route::prefix('affiliate-reports')->name('affiliate-reports.')->group(function () {
        Route::get('/', [AffiliateReportController::class, 'index'])->name('index');
        Route::get('/detailed', [AffiliateReportController::class, 'detailed'])->name('detailed');
        Route::get('/export/csv', [AffiliateReportController::class, 'export'])->name('export');
    });

    // File Manager
    Route::prefix('files')->name('files.')->group(function () {
        Route::get('/', [FileController::class, 'index'])->name('index');
        Route::post('/upload', [FileController::class, 'upload'])->name('upload');
        Route::delete('/delete', [FileController::class, 'delete'])->name('delete');
        Route::post('/folder', [FileController::class, 'createFolder'])->name('folder.create');
        Route::post('/rename', [FileController::class, 'rename'])->name('rename');
        Route::post('/move', [FileController::class, 'move'])->name('move');
        Route::get('/download/{file}', [FileController::class, 'download'])->name('download');
    });

    // Reports & Analytics
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/products', [ReportController::class, 'products'])->name('products');
        Route::get('/vendors', [ReportController::class, 'vendors'])->name('vendors');
        Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
        Route::get('/orders', [ReportController::class, 'orders'])->name('orders');
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
        Route::get('/commissions', [ReportController::class, 'commissions'])->name('commissions');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
    });

    // MLM Management (missing from admin.php)
    Route::prefix('mlm')->name('mlm.')->group(function () {
        Route::get('/genealogy', function () { return view('admin.mlm.genealogy'); })->name('genealogy');
        Route::get('/ranks', function () { return view('admin.mlm.ranks'); })->name('ranks');
        Route::get('/bonuses', function () { return view('admin.mlm.bonuses'); })->name('bonuses');
        Route::get('/downlines', function () { return view('admin.mlm.downlines'); })->name('downlines');
        Route::get('/pv-points', function () { return view('admin.mlm.pv-points'); })->name('pv-points');
    });

    // MLM Bonus Settings
    Route::prefix('mlm-bonus-settings')->name('mlm-bonus-settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\MlmBonusSettingController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\MlmBonusSettingController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\MlmBonusSettingController::class, 'store'])->name('store');
        Route::get('/{mlmBonusSetting}', [\App\Http\Controllers\Admin\MlmBonusSettingController::class, 'show'])->name('show');
        Route::get('/{mlmBonusSetting}/edit', [\App\Http\Controllers\Admin\MlmBonusSettingController::class, 'edit'])->name('edit');
        Route::put('/{mlmBonusSetting}', [\App\Http\Controllers\Admin\MlmBonusSettingController::class, 'update'])->name('update');
        Route::delete('/{mlmBonusSetting}', [\App\Http\Controllers\Admin\MlmBonusSettingController::class, 'destroy'])->name('destroy');
        Route::post('/{mlmBonusSetting}/toggle-status', [\App\Http\Controllers\Admin\MlmBonusSettingController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [\App\Http\Controllers\Admin\MlmBonusSettingController::class, 'bulkAction'])->name('bulk-action');
        Route::post('/initialize-defaults', [\App\Http\Controllers\Admin\MlmBonusSettingController::class, 'initializeDefaults'])->name('initialize-defaults');
    });



    // Product Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/check-slug', [ProductController::class, 'checkSlug'])->name('check-slug');
        Route::get('/subcategories/{categoryId}', [ProductController::class, 'getSubcategories'])->name('subcategories');
        Route::post('/bulk-action', [ProductController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/bulk-import', [ProductController::class, 'bulkImport'])->name('bulk-import');
        Route::post('/bulk-import', [ProductController::class, 'processBulkImport'])->name('bulk-import.process');
        Route::get('/export', [ProductController::class, 'showExportPage'])->name('export');
        Route::get('/export-download', [ProductController::class, 'export'])->name('export-download');
        Route::get('/analytics', [ProductController::class, 'analytics'])->name('analytics');
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{id}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('toggle-featured');
        
        // Image management routes
        Route::post('/{id}/replace-all-images', [ProductController::class, 'replaceAllImages'])->name('replace-all-images');
        Route::delete('/{id}/delete-image', [ProductController::class, 'deleteImage'])->name('delete-image');
        Route::post('/upload-image', [ProductController::class, 'uploadImage'])->name('upload-image');
        
        // Product Specifications Management
        Route::prefix('specifications')->name('specifications.')->group(function () {
            Route::get('/index', [ProductSpecificationController::class, 'index'])->name('index');
            Route::get('/{id}/edit', [ProductSpecificationController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ProductSpecificationController::class, 'update'])->name('update');
            Route::post('/bulk-update', [ProductSpecificationController::class, 'bulkUpdate'])->name('bulk-update');
            Route::get('/bulk', [ProductSpecificationController::class, 'bulk'])->name('bulk');
            Route::get('/missing', [ProductSpecificationController::class, 'missing'])->name('missing');
            Route::get('/generate', [ProductSpecificationController::class, 'generate'])->name('generate');
            Route::post('/generate', [ProductSpecificationController::class, 'generateSpecs'])->name('generate.post');
        });
    });

    // Order Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        
        // Search endpoints for order creation
        Route::get('/search-customers', [OrderController::class, 'searchCustomers'])->name('searchCustomers');
        Route::get('/search-products', [OrderController::class, 'searchProducts'])->name('searchProducts');
        Route::get('/get-categories', [OrderController::class, 'getCategories'])->name('getCategories');
        
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::put('/{id}', [OrderController::class, 'update'])->name('update');
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/update-customer-info', [OrderController::class, 'updateCustomerInfo'])->name('update-customer-info');
        Route::post('/{id}/update-shipping-address', [OrderController::class, 'updateShippingAddress'])->name('update-shipping-address');
        Route::post('/{id}/update-billing-address', [OrderController::class, 'updateBillingAddress'])->name('update-billing-address');
        Route::post('/bulk-update', [OrderController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/pending', [OrderController::class, 'pending'])->name('pending');
        Route::get('/processing', [OrderController::class, 'processing'])->name('processing');
        Route::get('/shipped', [OrderController::class, 'shipped'])->name('shipped');
        Route::get('/delivered', [OrderController::class, 'delivered'])->name('delivered');
        Route::get('/cancelled', [OrderController::class, 'cancelled'])->name('cancelled');
        Route::get('/refunded', [OrderController::class, 'refunded'])->name('refunded');
        Route::post('/{id}/change-status', [OrderController::class, 'changeStatus'])->name('change-status');
        Route::post('/{id}/send-invoice-email', [OrderController::class, 'sendInvoiceEmail'])->name('send-invoice-email');
        Route::post('/{id}/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('update-payment-status');
        Route::get('/{id}/payment-details', [OrderController::class, 'getPaymentDetails'])->name('payment-details');
        Route::post('/{id}/add-note', [OrderController::class, 'addNote'])->name('add-note');
        Route::post('/{id}/send-email', [OrderController::class, 'sendEmail'])->name('send-email');
        Route::post('/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('cancel');
        Route::post('/{id}/assign-vendor', [OrderController::class, 'assignVendor'])->name('assign-vendor');
        Route::post('/bulk-action', [OrderController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [OrderController::class, 'export'])->name('export');
        Route::get('/analytics', [OrderController::class, 'analytics'])->name('analytics');
        Route::get('/{id}/invoice', [OrderController::class, 'invoice'])->name('invoice');
        Route::get('/{id}/tracking', [OrderController::class, 'tracking'])->name('tracking');
        Route::get('/{id}/professional-invoice', [OrderController::class, 'generateProfessionalInvoice'])->name('professional-invoice');
        Route::get('/{id}/professional-invoice/download', [OrderController::class, 'downloadProfessionalInvoice'])->name('professional-invoice.download');
        Route::get('/{id}/printable-invoice', [OrderController::class, 'printableInvoice'])->name('printable-invoice');
        Route::get('/{id}/printable-invoice/download', [OrderController::class, 'downloadPrintableInvoice'])->name('printable-invoice.download');
        Route::get('/{id}/print', [OrderController::class, 'printableInvoice'])->name('print');
        Route::get('/{id}/simple-invoice', [OrderController::class, 'generateSimpleInvoice'])->name('simple-invoice');
        Route::get('/{id}/simple-invoice/download', [OrderController::class, 'downloadSimpleInvoice'])->name('simple-invoice.download');
    });

    // Delivery Charges Management
    Route::prefix('delivery-charges')->name('delivery-charges.')->group(function () {
        Route::get('/', [DeliveryChargeController::class, 'index'])->name('index');
        Route::get('/create', [DeliveryChargeController::class, 'create'])->name('create');
        Route::post('/', [DeliveryChargeController::class, 'store'])->name('store');
        Route::get('/{deliveryCharge}', [DeliveryChargeController::class, 'show'])->name('show');
        Route::get('/{deliveryCharge}/edit', [DeliveryChargeController::class, 'edit'])->name('edit');
        Route::put('/{deliveryCharge}', [DeliveryChargeController::class, 'update'])->name('update');
        Route::delete('/{deliveryCharge}', [DeliveryChargeController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-import', [DeliveryChargeController::class, 'bulkImport'])->name('bulk-import');
    });

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/datatable', [UserController::class, 'getUsersDataTable'])->name('datatable');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{id}/ban', [UserController::class, 'ban'])->name('ban');
        Route::post('/{id}/unban', [UserController::class, 'unban'])->name('unban');
        Route::post('/bulk-action', [UserController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [UserController::class, 'export'])->name('export');
        Route::get('/analytics', [UserController::class, 'analytics'])->name('analytics');
        Route::get('/search-sponsor', [UserController::class, 'searchSponsor'])->name('search-sponsor');
        Route::post('/validate-email', [UserController::class, 'validateEmail'])->name('validate-email');
        Route::post('/validate-mobile', [UserController::class, 'validateMobile'])->name('validate-mobile');
        Route::post('/validate-sponsor', [UserController::class, 'validateSponsorId'])->name('validate-sponsor');
        Route::post('/validate-username', [UserController::class, 'validateUsername'])->name('validate-username');
        Route::post('/validate-sponsor-username', [UserController::class, 'validateSponsorUsername'])->name('validate-sponsor-username');
        
        // MLM Binary Tree Placement Routes
        Route::post('/check-position-availability', [UserController::class, 'checkPositionAvailability'])->name('check-position-availability');
        Route::post('/validate-placement', [UserController::class, 'validatePlacement'])->name('validate-placement');
        Route::get('/find-auto-placement/{sponsorId}/{position}', [UserController::class, 'findNextAvailablePositionPublic'])->name('find-auto-placement');
    });

    // Vendor Management
    Route::prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::get('/create', [VendorController::class, 'create'])->name('create');
        Route::post('/', [VendorController::class, 'store'])->name('store');
        Route::get('/pending', [VendorController::class, 'pending'])->name('pending');
        Route::get('/approved', [VendorController::class, 'approved'])->name('approved');
        Route::get('/suspended', [VendorController::class, 'suspended'])->name('suspended');
        Route::get('/commissions', [VendorController::class, 'commissions'])->name('commissions');
        
        // Vendor Applications
        Route::get('/applications', [VendorController::class, 'applications'])->name('applications');
        Route::get('/applications/{id}', [VendorController::class, 'showApplication'])->name('applications.show');
        Route::post('/applications/{id}/approve', [VendorController::class, 'approveApplication'])->name('applications.approve');
        Route::post('/applications/{id}/reject', [VendorController::class, 'rejectApplication'])->name('applications.reject');
        
        Route::get('/{id}', [VendorController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [VendorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VendorController::class, 'update'])->name('update');
        Route::delete('/{id}', [VendorController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/approve', [VendorController::class, 'approve'])->name('approve');
        Route::post('/{id}/suspend', [VendorController::class, 'suspend'])->name('suspend');
        Route::post('/{id}/reject', [VendorController::class, 'reject'])->name('reject');
        Route::post('/bulk-action', [VendorController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [VendorController::class, 'export'])->name('export');
        Route::get('/analytics', [VendorController::class, 'analytics'])->name('analytics');
    });

    // Tags Management
    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::get('/create', [TagController::class, 'create'])->name('create');
        Route::post('/', [TagController::class, 'store'])->name('store');
        Route::post('/validate-slug', [TagController::class, 'validateSlug'])->name('validate-slug');
        Route::get('/{id}', [TagController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [TagController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TagController::class, 'update'])->name('update');
        Route::delete('/{id}', [TagController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [TagController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [TagController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [TagController::class, 'export'])->name('export');
        Route::get('/analytics', [TagController::class, 'analytics'])->name('analytics');
        Route::get('/popular', [TagController::class, 'popular'])->name('popular');
    });

    // Collections Management
    Route::prefix('collections')->name('collections.')->group(function () {
        Route::get('/', [CollectionController::class, 'index'])->name('index');
        Route::get('/create', [CollectionController::class, 'create'])->name('create');
        Route::post('/', [CollectionController::class, 'store'])->name('store');
        Route::get('/analytics', [CollectionController::class, 'analytics'])->name('analytics');
        Route::get('/featured', [CollectionController::class, 'featured'])->name('featured');
        Route::get('/seasonal', [CollectionController::class, 'seasonal'])->name('seasonal');
        Route::get('/export/csv', [CollectionController::class, 'export'])->name('export');
        Route::post('/validate-slug', [CollectionController::class, 'validateSlug'])->name('validate-slug');
        Route::post('/bulk-action', [CollectionController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/{collection}', [CollectionController::class, 'show'])->name('show');
        Route::get('/{collection}/edit', [CollectionController::class, 'edit'])->name('edit');
        Route::put('/{collection}', [CollectionController::class, 'update'])->name('update');
        Route::delete('/{collection}', [CollectionController::class, 'destroy'])->name('destroy');
        Route::post('/{collection}/toggle-status', [CollectionController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Units Management
    Route::prefix('units')->name('units.')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('index');
        Route::get('/create', [UnitController::class, 'create'])->name('create');
        Route::post('/', [UnitController::class, 'store'])->name('store');
        Route::get('/analytics', [UnitController::class, 'analytics'])->name('analytics');
        Route::get('/weight', [UnitController::class, 'weight'])->name('weight');
        Route::get('/dimensions', [UnitController::class, 'dimensions'])->name('dimensions');
        Route::get('/export/csv', [UnitController::class, 'export'])->name('export');
        Route::post('/bulk-action', [UnitController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/{unit}', [UnitController::class, 'show'])->name('show');
        Route::get('/{unit}/edit', [UnitController::class, 'edit'])->name('edit');
        Route::put('/{unit}', [UnitController::class, 'update'])->name('update');
        Route::delete('/{unit}', [UnitController::class, 'destroy'])->name('destroy');
        Route::post('/{unit}/toggle-status', [UnitController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // Customer Management
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{id}', [CustomerController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [CustomerController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [CustomerController::class, 'export'])->name('export');
        Route::get('/analytics', [CustomerController::class, 'analytics'])->name('analytics');
    });

    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', function () { return view('admin.subscriptions.index'); })->name('index');
        Route::get('/create', function () { return view('admin.subscriptions.create'); })->name('create');
        Route::get('/subscribers', function () { return view('admin.subscriptions.subscribers'); })->name('subscribers');
        Route::get('/renewals', function () { return view('admin.subscriptions.renewals'); })->name('renewals');
    });

    // Inventory Management
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/stock', [InventoryController::class, 'stock'])->name('stock');
        Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('low-stock');
        Route::get('/out-of-stock', [InventoryController::class, 'outOfStock'])->name('out-of-stock');
        Route::get('/adjustments', [InventoryController::class, 'adjustments'])->name('adjustments');
        Route::post('/adjustments', [InventoryController::class, 'storeAdjustment'])->name('adjustments.store');
        Route::get('/movements', [InventoryController::class, 'movements'])->name('movements');
        Route::get('/warehouses', [InventoryController::class, 'warehouses'])->name('warehouses');
        Route::post('/warehouses', [InventoryController::class, 'storeWarehouse'])->name('warehouses.store');
        Route::get('/export/csv', [InventoryController::class, 'export'])->name('export');
        Route::get('/analytics', [InventoryController::class, 'analytics'])->name('analytics');
    });

    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/pending', [ReviewController::class, 'pending'])->name('pending');
        Route::get('/featured', [ReviewController::class, 'featured'])->name('featured');
        Route::get('/analytics', [ReviewController::class, 'analytics'])->name('analytics');
        Route::post('/{review}/status', [ReviewController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
    });

    // Invoice Management
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/download', [InvoiceController::class, 'downloadInvoice'])->name('download');
        Route::get('/{id}/generate', [InvoiceController::class, 'generateInvoice'])->name('generate');
        Route::get('/{id}/professional', [InvoiceController::class, 'generateProfessionalInvoice'])->name('professional');
        Route::get('/{id}/professional/download', [InvoiceController::class, 'downloadProfessionalInvoice'])->name('professional.download');
        Route::get('/{id}/print', [InvoiceController::class, 'printInvoice'])->name('print');
        Route::get('/{id}/preview', [InvoiceController::class, 'invoicePreview'])->name('preview');
        Route::post('/{id}/email', [InvoiceController::class, 'emailInvoice'])->name('email');
        Route::post('/{id}/reminder', [InvoiceController::class, 'sendReminder'])->name('reminder');
        Route::post('/{id}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('mark-paid');
        Route::get('/{id}/duplicate', [InvoiceController::class, 'duplicate'])->name('duplicate');
        Route::post('/{id}/customize', [InvoiceController::class, 'customizeInvoice'])->name('customize');
        Route::post('/bulk-invoices', [InvoiceController::class, 'bulkInvoices'])->name('bulk');
        Route::get('/analytics', [InvoiceController::class, 'invoiceAnalytics'])->name('analytics');
    });

    // Invoice API Routes
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/invoice-stats', [InvoiceController::class, 'getInvoiceStats'])->name('invoice-stats');
    });

    // Marketing Management
    Route::prefix('marketing')->name('marketing.')->group(function () {
        Route::get('/', [MarketingController::class, 'index'])->name('index');
        Route::get('/banners', [MarketingController::class, 'banners'])->name('banners');
        Route::get('/promotions', [MarketingController::class, 'promotions'])->name('promotions');
        Route::post('/promotions', [MarketingController::class, 'storePromotion'])->name('promotions.store');
        Route::get('/newsletters', [MarketingController::class, 'newsletters'])->name('newsletters');
        Route::post('/newsletters', [MarketingController::class, 'sendNewsletter'])->name('newsletters.send');
        Route::get('/campaigns', [MarketingController::class, 'campaigns'])->name('campaigns');
        Route::post('/campaigns', [MarketingController::class, 'storeCampaign'])->name('campaigns.store');
        Route::get('/seo', [MarketingController::class, 'seo'])->name('seo');
        Route::post('/seo', [MarketingController::class, 'updateSeo'])->name('seo.update');
        Route::get('/social', [MarketingController::class, 'social'])->name('social');
        Route::post('/social', [MarketingController::class, 'updateSocial'])->name('social.update');
        Route::get('/analytics', [MarketingController::class, 'analytics'])->name('analytics');
    });

    // Website Management
    Route::prefix('website')->name('website.')->group(function () {
        Route::get('/', [WebsiteController::class, 'index'])->name('index');
        Route::get('/pages', [WebsiteController::class, 'pages'])->name('pages');
        Route::get('/pages/create', [WebsiteController::class, 'createPage'])->name('pages.create');
        Route::post('/pages', [WebsiteController::class, 'storePage'])->name('pages.store');
        Route::put('/pages/{id}', [WebsiteController::class, 'updatePage'])->name('pages.update');
        Route::delete('/pages/{id}', [WebsiteController::class, 'deletePage'])->name('pages.delete');
        Route::get('/menus', [WebsiteController::class, 'menus'])->name('menus');
        Route::post('/menus', [WebsiteController::class, 'storeMenu'])->name('menus.store');
        Route::put('/menus/{id}', [WebsiteController::class, 'updateMenu'])->name('menus.update');
        Route::get('/themes', [WebsiteController::class, 'themes'])->name('themes');
        Route::post('/themes/activate', [WebsiteController::class, 'activateTheme'])->name('themes.activate');
        Route::get('/seo', [WebsiteController::class, 'seo'])->name('seo');
        Route::post('/seo', [WebsiteController::class, 'updateSeo'])->name('seo.update');
    });

    // Image Upload Routes
    Route::prefix('image-upload')->name('image-upload.')->group(function () {
        // Demo and Guide routes
        Route::get('/demo', [ImageUploadController::class, 'demo'])->name('demo');
        Route::get('/usage', function () {
            return view('admin.image-upload.usage');
        })->name('usage');
        Route::get('/resize-guide', function () {
            return view('admin.image-upload.resize-guide');
        })->name('resize-guide');
        
        // API routes
        Route::post('/multiple', [ImageUploadController::class, 'uploadMultiple'])->name('multiple');
        Route::post('/single', [ImageUploadController::class, 'uploadSingle'])->name('single');
        Route::delete('/delete', [ImageUploadController::class, 'deleteImage'])->name('delete');
        Route::post('/resize', [ImageUploadController::class, 'resizeImages'])->name('resize');
        Route::post('/thumbnails', [ImageUploadController::class, 'generateThumbnails'])->name('thumbnails');
        Route::match(['GET', 'POST'], '/info', [ImageUploadController::class, 'getImageInfo'])->name('info');
        Route::post('/optimize', [ImageUploadController::class, 'optimizeImages'])->name('optimize');
        Route::post('/download-zip', [ImageUploadController::class, 'downloadZip'])->name('download-zip');
    });
    
    // Test upload route
    Route::get('/test-upload', function() {
        return view('admin.test-upload');
    })->name('test-upload');
    
    // Debug route to test image upload controller
    Route::get('/debug-optimize', function() {
        return response()->json([
            'success' => true,
            'message' => 'Debug route working',
            'controller_exists' => class_exists(\App\Http\Controllers\Admin\ImageUploadController::class),
            'method_exists' => method_exists(\App\Http\Controllers\Admin\ImageUploadController::class, 'optimizeImages'),
            'route_name' => route('admin.image-upload.optimize')
        ]);
    })->name('debug-optimize');
    
    // Search functionality
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/', [SearchController::class, 'index'])->name('index');
        Route::post('/global', [SearchController::class, 'globalSearch'])->name('global');
        Route::get('/products', [SearchController::class, 'products'])->name('products');
        Route::get('/users', [SearchController::class, 'users'])->name('users');
        Route::get('/orders', [SearchController::class, 'orders'])->name('orders');
        Route::get('/vendors', [SearchController::class, 'vendors'])->name('vendors');
    });

    // Admin Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::get('/recent', [AdminNotificationController::class, 'recent'])->name('recent');
        Route::get('/unread-count', [AdminNotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/{id}/mark-read', [AdminNotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('api')->name('api.')->group(function () {
        // Search endpoints
        Route::get('/search/vendors', function () {
            return response()->json([
                ['id' => 1, 'name' => 'Tech Store'],
                ['id' => 2, 'name' => 'Fashion Hub'],
                ['id' => 3, 'name' => 'Book World']
            ]);
        })->name('search.vendors');

        Route::get('/search/customers', function () {
            return response()->json([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Smith'],
                ['id' => 3, 'name' => 'Bob Wilson']
            ]);
        })->name('search.customers');

        Route::get('/search/categories', function () {
            return response()->json([
                ['id' => 1, 'name' => 'Electronics'],
                ['id' => 2, 'name' => 'Fashion'],
                ['id' => 3, 'name' => 'Books']
            ]);
        })->name('search.categories');

        // Attribute values by attribute
        Route::get('/attributes/{id}/values', [AttrvaluesController::class, 'getByAttribute'])->name('attributes.values');
        
        // Category children
        Route::get('/categories/{id}/children', [SubcategoryController::class, 'getChildren'])->name('categories.children');
    });

    // Admin Menu Management
    Route::prefix('menu')->name('menu.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminMenuController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\AdminMenuController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\AdminMenuController::class, 'store'])->name('store');
        Route::get('/builder', [App\Http\Controllers\Admin\AdminMenuController::class, 'builder'])->name('builder');
        Route::post('/update-order', [App\Http\Controllers\Admin\AdminMenuController::class, 'updateOrder'])->name('update-order');
        Route::get('/export', [App\Http\Controllers\Admin\AdminMenuController::class, 'export'])->name('export');
        Route::post('/import', [App\Http\Controllers\Admin\AdminMenuController::class, 'import'])->name('import');
        Route::get('/demo', function () {
            return view('admin.menu.demo');
        })->name('demo');
        Route::get('/settings', function () {
            return view('admin.menu.settings');
        })->name('settings');
        Route::post('/clear-cache', [App\Http\Controllers\Admin\AdminMenuController::class, 'clearCache'])->name('clear-cache');
        Route::get('/{menu}', [App\Http\Controllers\Admin\AdminMenuController::class, 'show'])->name('show');
        Route::get('/{menu}/edit', [App\Http\Controllers\Admin\AdminMenuController::class, 'edit'])->name('edit');
        Route::put('/{menu}', [App\Http\Controllers\Admin\AdminMenuController::class, 'update'])->name('update');
        Route::delete('/{menu}', [App\Http\Controllers\Admin\AdminMenuController::class, 'destroy'])->name('destroy');
        Route::post('/{menu}/toggle-status', [App\Http\Controllers\Admin\AdminMenuController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{menu}/duplicate', [App\Http\Controllers\Admin\AdminMenuController::class, 'duplicate'])->name('duplicate');
    });

    /*
    |--------------------------------------------------------------------------
    | Package Link Sharing Management Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('package-link-sharing')->name('package-link-sharing.')->group(function () {
        Route::get('/', [AdminPackageLinkSharingController::class, 'index'])->name('index');
        Route::get('/create', [AdminPackageLinkSharingController::class, 'create'])->name('create');
        Route::post('/', [AdminPackageLinkSharingController::class, 'store'])->name('store');
        Route::get('/{packageLinkSharingSetting}', [AdminPackageLinkSharingController::class, 'show'])->name('show');
        Route::get('/{packageLinkSharingSetting}/edit', [AdminPackageLinkSharingController::class, 'edit'])->name('edit');
        Route::put('/{packageLinkSharingSetting}', [AdminPackageLinkSharingController::class, 'update'])->name('update');
        Route::delete('/{packageLinkSharingSetting}', [AdminPackageLinkSharingController::class, 'destroy'])->name('destroy');
        Route::post('/{packageLinkSharingSetting}/toggle-active', [AdminPackageLinkSharingController::class, 'toggleActive'])->name('toggle-active');
        Route::get('/reports/statistics', [AdminPackageLinkSharingController::class, 'statistics'])->name('statistics');
    });
}); // End of Admin Protected Routes

// Public API routes (for frontend integration)
Route::prefix('api/frontend')->name('frontend.api.')->group(function () {
    // Banner display
    Route::get('/banners/{position}', [BannerController::class, 'getByPosition'])->name('banners.by-position');
    Route::post('/banners/{id}/impression', [BannerController::class, 'trackImpression'])->name('banners.impression');
    Route::post('/banners/{id}/click', [BannerController::class, 'trackClick'])->name('banners.click');

    // Popup display
    Route::get('/popups/active', [PopupController::class, 'getActive'])->name('popups.active');
    Route::post('/popups/{id}/track-show', [PopupController::class, 'trackShow'])->name('popups.track-show');
    Route::post('/popups/{id}/track-click', [PopupController::class, 'trackClick'])->name('popups.track-click');
    Route::post('/popups/{id}/track-close', [PopupController::class, 'trackClose'])->name('popups.track-close');

    // Support ticket creation
    Route::post('/support/tickets', [SupportController::class, 'createPublicTicket'])->name('support.create-ticket');
});
