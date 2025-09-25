<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Vendor\ReportController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\SettingsController;
use App\Http\Controllers\Vendor\TransferController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\VendorKycController;
use App\Http\Controllers\Vendor\Auth\VendorAuthController;

/*
|--------------------------------------------------------------------------
| Vendor Routes
|--------------------------------------------------------------------------
|
| Here is where you can register vendor routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

Route::prefix('vendor')->name('vendor.')->group(function () {
    Route::middleware(['auth', 'role.session:vendor'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/refresh-balance', [DashboardController::class, 'refreshBalance'])->name('dashboard.refresh-balance');
        
        // Profile Management
        Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
        
        // KYC Verification
        Route::prefix('kyc')->name('kyc.')->group(function () {
            Route::get('/', [VendorKycController::class, 'index'])->name('index');
            Route::get('/step/{step}', [VendorKycController::class, 'step'])->name('step');
            Route::post('/step/{step}', [VendorKycController::class, 'saveStep'])->name('save-step');
            Route::post('/upload-document', [VendorKycController::class, 'uploadDocument'])->name('upload-document');
            Route::delete('/delete-document', [VendorKycController::class, 'deleteDocument'])->name('delete-document');
            Route::get('/status', [VendorKycController::class, 'status'])->name('status');
            Route::post('/update-profile', [VendorKycController::class, 'updateProfile'])->name('update-profile');
            Route::get('/certificate', [VendorKycController::class, 'certificate'])->name('certificate');
            Route::post('/resubmit', [VendorKycController::class, 'resubmit'])->name('resubmit');
        });
        
        // Products Management
        Route::resource('products', ProductController::class);
        Route::post('/products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');
        
        // Orders Management
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/pending', [OrderController::class, 'pending'])->name('orders.pending');
        Route::get('/orders/completed', [OrderController::class, 'completed'])->name('orders.completed');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
        
        // Sales Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-sales', [ReportController::class, 'exportSales'])->name('reports.export-sales');
        
        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings/business', [SettingsController::class, 'updateBusiness'])->name('settings.business');
        Route::put('/settings/account', [SettingsController::class, 'updateAccount'])->name('settings.account');
        
        // Transfer Management
        Route::prefix('transfers')->name('transfers.')->group(function () {
            Route::get('/', [TransferController::class, 'index'])->name('index');
            Route::get('/search-members', [TransferController::class, 'searchMembers'])->name('search-members');
            Route::post('/send', [TransferController::class, 'transferToMember'])->name('send');
            Route::post('/retransfer', [TransferController::class, 'retransferToMember'])->name('retransfer');
            Route::get('/history', [TransferController::class, 'transferHistory'])->name('history');
            Route::get('/history-data', [TransferController::class, 'getTransferHistoryData'])->name('history-data');
            Route::get('/fund-requests', [TransferController::class, 'fundRequestsPage'])->name('fund-requests');
            Route::post('/process-fund-request', [TransferController::class, 'processFundRequest'])->name('process-fund-request');
            Route::get('/pending-requests', [TransferController::class, 'getPendingFundRequests'])->name('pending-requests');
            
            // Debug route
            Route::post('/test-send', function(\Illuminate\Http\Request $request) {
                Log::info('Test send route hit', $request->all());
                return response()->json(['success' => true, 'message' => 'Test route working']);
            })->name('test-send');
        });
        
        // Mini Vendor Management
        Route::prefix('mini-vendors')->name('mini-vendors.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Vendor\MiniVendorController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Vendor\MiniVendorController::class, 'create'])->name('create');
            Route::get('/search-users', [\App\Http\Controllers\Vendor\MiniVendorController::class, 'searchUsers'])->name('search-users');
            Route::post('/', [\App\Http\Controllers\Vendor\MiniVendorController::class, 'store'])->name('store');
            Route::get('/{miniVendor}', [\App\Http\Controllers\Vendor\MiniVendorController::class, 'show'])->name('show');
            Route::put('/{miniVendor}/status', [\App\Http\Controllers\Vendor\MiniVendorController::class, 'updateStatus'])->name('update-status');
            Route::delete('/{miniVendor}', [\App\Http\Controllers\Vendor\MiniVendorController::class, 'destroy'])->name('destroy');
            Route::get('/dashboard/stats', [\App\Http\Controllers\Vendor\MiniVendorController::class, 'dashboard'])->name('dashboard');
            Route::get('/commission/stats', [\App\Http\Controllers\Vendor\MiniVendorController::class, 'commissionStats'])->name('commission-stats');
        });
        
        Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
        Route::put('/settings/shipping', [SettingsController::class, 'updateShipping'])->name('settings.shipping');
        
        // Vendor Logout (Additional route for vendor-specific logout handling)
        Route::post('/logout', [VendorAuthController::class, 'logout'])->name('logout.vendor');
        Route::post('/ajax-logout', [VendorAuthController::class, 'ajaxLogout'])->name('ajax.logout');
    });
});
