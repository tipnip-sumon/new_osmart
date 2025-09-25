<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\UserController;
use App\Http\Controllers\Member\ProductController;
use App\Http\Controllers\Member\AffiliateController;
use App\Http\Controllers\Member\OrderController;
use App\Http\Controllers\Member\SearchController;
use App\Http\Controllers\Member\MatchingController;
use App\Http\Controllers\Member\InvestController;
use App\Http\Controllers\Member\MemberHeaderController;
use App\Http\Controllers\Member\FundRequestController;
use App\Http\Controllers\Member\EmailVerificationController;

/*
|--------------------------------------------------------------------------
| Member Routes (Protected - For Affiliates)
|--------------------------------------------------------------------------
|
| All routes in this file require authentication and are prefixed with 'member'
| These routes are for authenticated members/affiliates only
|
*/

Route::middleware(['auth', 'role.session:affiliate'])->prefix('member')->name('member.')->group(function () {
    
    // Dashboard & Main Pages
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/wallet/balance', [UserController::class, 'getWalletBalance'])->name('wallet.balance');
    
    
    
    // Vendor Application (Affiliate members can apply to become vendors)
    Route::get('/vendor-application', [UserController::class, 'vendorApplication'])->name('vendor-application');
    Route::post('/vendor-application', [UserController::class, 'submitVendorApplication'])->name('vendor-application.submit');
    
    // CSRF Token refresh endpoint
    Route::get('/csrf-token', function() {
        return response()->json(['token' => csrf_token()]);
    })->name('csrf-token');
    
    // Search Routes
    Route::get('/search/live', [SearchController::class, 'liveSearch'])->name('search.live');
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/products/search', [SearchController::class, 'search'])->name('products.search');
    
    // MLM Tree & Network
    Route::get('/genealogy', [UserController::class, 'genealogy'])->name('genealogy');
    Route::post('/genealogy/node', [UserController::class, 'getGenealogyNode'])->name('genealogy.node');
    Route::post('/genealogy/search', [UserController::class, 'searchGenealogyMembers'])->name('genealogy.search');
    Route::get('/genealogy/compact-data', [UserController::class, 'getCompactData'])->name('genealogy.compact');
    Route::get('/genealogy/level-data', [UserController::class, 'getLevelData'])->name('genealogy.level');
    Route::get('/genealogy/hierarchy-data', [UserController::class, 'getHierarchyData'])->name('genealogy.hierarchy');
    Route::get('/genealogy/member/{id}', [UserController::class, 'getMemberDetails'])->name('genealogy.member');
    Route::get('/binary', [UserController::class, 'binary'])->name('binary');
    Route::get('/sponsor', [UserController::class, 'sponsor'])->name('sponsor');
    Route::get('/generations', [UserController::class, 'generations'])->name('generations');
    Route::post('/generations/downline', [UserController::class, 'getUserDownline'])->name('generations.downline');
    
    // Financial Management
    Route::get('/commissions', [UserController::class, 'commissions'])->name('commissions');
    
   
    
    // Withdrawal & Transfer
    Route::get('/withdraw', [UserController::class, 'withdraw'])->name('withdraw');
    Route::post('/withdraw', [UserController::class, 'processWithdraw'])->name('withdraw.store');
    Route::get('/withdraw/history', [UserController::class, 'withdrawHistory'])->name('withdraw.history');
    Route::get('/withdraw/{id}/details', [UserController::class, 'withdrawDetails'])->name('withdraw.details');
    Route::post('/withdraw/{id}/cancel', [UserController::class, 'cancelWithdraw'])->name('withdraw.cancel');
    Route::get('/transfer', [UserController::class, 'transfer'])->name('transfer');
    Route::post('/transfer', [UserController::class, 'processTransfer'])->name('transfer.process');
    Route::get('/transfer/search-users', [UserController::class, 'searchUsers'])->name('transfer.search-users');
    Route::get('/transfer/fee-info', [UserController::class, 'getTransferFeeInfo'])->name('transfer.fee-info');
    Route::get('/withdraw/fee-info', [UserController::class, 'getWithdrawalFeeInfo'])->name('withdraw.fee-info');
    Route::get('/wallet', [UserController::class, 'wallet'])->name('wallet');
    
    // Fund Request Management
    Route::prefix('fund-requests')->name('fund-requests.')->group(function () {
        Route::get('/', [FundRequestController::class, 'index'])->name('index');
        Route::post('/create', [FundRequestController::class, 'createRequest'])->name('create');
        Route::get('/history', [FundRequestController::class, 'getRequestHistory'])->name('history');
        Route::post('/cancel', [FundRequestController::class, 'cancelRequest'])->name('cancel');
        Route::get('/vendors', [FundRequestController::class, 'getAvailableVendors'])->name('vendors');
        Route::get('/statistics', [FundRequestController::class, 'getStatistics'])->name('statistics');
    });
    
    // Fund Management
    Route::get('/add-fund', [UserController::class, 'addFund'])->name('add-fund');
    Route::post('/add-fund', [UserController::class, 'processAddFund'])->name('add-fund.store');
    Route::get('/fund-history', [UserController::class, 'fundHistory'])->name('fund-history');
    Route::get('/fund-history/{id}', [UserController::class, 'getFundTransaction'])->name('fund-history.show');
    
    // Rank & Achievements
    Route::get('/rank', [UserController::class, 'rank'])->name('rank');

    // Account Upgrade / Plan Purchase
    Route::get('/plan-purchase', function () {
        return view('member.plan_purchase');
    })->name('plan_purchase');
    
    // Investment Routes
    Route::get('/invest', [InvestController::class, 'dashboard'])->name('invest');
    Route::prefix('invest')->name('invest.')->group(function () {
        Route::get('/list', [InvestController::class, 'index'])->name('index');
        Route::get('/dashboard', [InvestController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [InvestController::class, 'create'])->name('create');
        Route::post('/store', [InvestController::class, 'store'])->name('store');
        Route::get('/statistics/data', [InvestController::class, 'statistics'])->name('statistics');
        Route::get('/{investment}', [InvestController::class, 'show'])->name('show');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [UserController::class, 'salesReport'])->name('sales');
        Route::get('/commission', [UserController::class, 'commissionReport'])->name('commission');
        Route::get('/team', [UserController::class, 'teamReport'])->name('team');
        Route::get('/payout', [UserController::class, 'payoutReport'])->name('payout');
    });
    
    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/search/products', [OrderController::class, 'searchProducts'])->name('search.products');
        Route::get('/product/{product}/details', [OrderController::class, 'getProductDetails'])->name('product.details');
        Route::get('/payment-method/{code}/details', [OrderController::class, 'getPaymentMethodDetails'])->name('payment.method.details');
        Route::get('/delivery-charge', [OrderController::class, 'getDeliveryCharge'])->name('delivery.charge');
        Route::get('/delivery-charges/all', [OrderController::class, 'getAllDeliveryCharges'])->name('delivery.charges.all');
    });
    
    // Products Routes - Main index route
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    
    // Products Routes - prefix group
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/search', [ProductController::class, 'search'])->name('search');
        Route::get('/favorites', [ProductController::class, 'favorites'])->name('favorites');
        Route::post('/favorites/add', [ProductController::class, 'addToFavorites'])->name('favorites.add');
        Route::post('/favorites/remove', [ProductController::class, 'removeFromFavorites'])->name('favorites.remove');
        Route::post('/favorites/toggle', [ProductController::class, 'toggleFavorite'])->name('favorites.toggle');
        Route::post('/favorites/bulk-remove', [ProductController::class, 'bulkRemoveFavorites'])->name('favorites.bulk-remove');
        Route::get('/shared', [ProductController::class, 'sharedProducts'])->name('shared');
        Route::get('/commissions', [ProductController::class, 'productCommissions'])->name('commissions');
        Route::post('/affiliate-link', [ProductController::class, 'getAffiliateLink'])->name('affiliate.link');
        Route::post('/affiliate/bulk-links', [ProductController::class, 'bulkGenerateAffiliateLinks'])->name('affiliate.bulk-links');
        Route::get('/{product}/quick-view', [ProductController::class, 'quickView'])->name('quick-view');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    });
    
    // Support & Profile Management
    Route::get('/training', [UserController::class, 'training'])->name('training');
    Route::get('/support', [UserController::class, 'support'])->name('support');
    
    // KYC Verification Routes
    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Member\KycController::class, 'index'])->name('index');
        Route::get('/step/{step}', [\App\Http\Controllers\Member\KycController::class, 'step'])->name('step')
            ->where('step', '[1-5]');
        Route::post('/step/{step}', [\App\Http\Controllers\Member\KycController::class, 'saveStep'])->name('save-step')
            ->where('step', '[1-5]');
        Route::post('/upload-document', [\App\Http\Controllers\Member\KycController::class, 'uploadDocument'])->name('upload-document');
        Route::post('/delete-document', [\App\Http\Controllers\Member\KycController::class, 'deleteDocument'])->name('delete-document');
        Route::get('/status', [\App\Http\Controllers\Member\KycController::class, 'status'])->name('status');
        Route::get('/certificate', [\App\Http\Controllers\Member\KycController::class, 'certificate'])->name('certificate');
        Route::post('/resubmit', [\App\Http\Controllers\Member\KycController::class, 'resubmit'])->name('resubmit');
        Route::post('/update-profile', [\App\Http\Controllers\Member\KycController::class, 'updateProfile'])->name('update-profile');
        Route::post('/update-contact', [\App\Http\Controllers\Member\KycController::class, 'updateContactInfo'])->name('update-contact');
    });
    
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/avatar', [UserController::class, 'uploadAvatar'])->name('profile.avatar');
    Route::put('/profile/password', [MemberHeaderController::class, 'updatePassword'])->name('profile.password.update');

    // Phone Verification Routes
    Route::prefix('phone')->name('phone.')->group(function () {
        Route::post('/verify/send', [UserController::class, 'sendPhoneVerification'])->name('verify.send');
        Route::post('/verify/confirm', [UserController::class, 'confirmPhoneVerification'])->name('verify.confirm');
    });

    // Email Verification Routes
    Route::prefix('email')->name('email.')->group(function () {
        Route::get('/verify', [\App\Http\Controllers\Member\EmailVerificationController::class, 'notice'])->name('verify.notice');
        Route::post('/verify/send', [\App\Http\Controllers\Member\EmailVerificationController::class, 'send'])->name('verify.send');
        Route::get('/verify/{id}/{hash}', [\App\Http\Controllers\Member\EmailVerificationController::class, 'verify'])->name('verify.email')
            ->middleware('signed');
        Route::get('/verify/status', [\App\Http\Controllers\Member\EmailVerificationController::class, 'status'])->name('verify.status');
        Route::post('/update', [\App\Http\Controllers\Member\EmailVerificationController::class, 'updateEmail'])->name('update');
        Route::get('/stats', [\App\Http\Controllers\Member\EmailVerificationController::class, 'stats'])->name('stats');
    });

    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\Member\NotificationController::class, 'index'])->name('index');
        Route::get('/header', [App\Http\Controllers\Member\NotificationController::class, 'getHeaderNotifications'])->name('header');
        Route::post('/{notificationId}/read', [App\Http\Controllers\Member\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [App\Http\Controllers\Member\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notificationId}', [App\Http\Controllers\Member\NotificationController::class, 'delete'])->name('delete');
        Route::get('/test', [App\Http\Controllers\Member\NotificationController::class, 'test'])->name('test'); // For development only
    });
});
