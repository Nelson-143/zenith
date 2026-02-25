<?php

use App\Http\Controllers\CustomerController;
use app\Http\Controllers\Dashboards\CategoryController; //
use App\Http\Controllers\Dashboards\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Order\DueOrderController;
use App\Http\Controllers\Order\OrderCompleteController;
use App\Http\Controllers\Order\OrderPendingController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductExportController;
use App\Http\Controllers\Product\ProductImportController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Purchase\PurchaseController;
use App\Http\Controllers\Quotation\QuotationController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinAssistController;
use app\Http\Controllers\DebtsController;  //
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\BudgetCategoryController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\AdsGeneratorController;
use App\Http\Controllers\ProfileCurrencyController;
use App\Http\Controllers\LiabilityController;
use App\Livewire\CreateOrder;
use App\Livewire\ShelfProducts;
use App\Livewire\LocationSetup;
use App\Livewire\Locations;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('php/', function () {
    return phpinfo();
});
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('front.about_master');
})->name('about_master.route');


Route::middleware(['auth','verified'])->group(function () {

   Route::get('dashboard/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales-data', [DashboardController::class, 'getSalesData']);

    // User Management
//     Route::resource('/users', UserController::class); //->except(['show']);
    Route::put('/user/change-password/{username}', [UserController::class, 'updatePassword'])->name('users.updatePassword');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::get('/profile/store-settings', [ProfileController::class, 'store_settings'])->name('profile.store.settings');
    Route::post('/profile/store-settings', [ProfileController::class, 'store_settings_store'])->name('profile.store.settings.store');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/quotations', QuotationController::class);
    Route::resource('/customers', CustomerController::class);
    Route::resource('/suppliers', SupplierController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/units', UnitController::class);

    // Route Products
Route::middleware(['auth', 'account.access'])->group(function () {
    Route::get('products/import/', [ProductImportController::class, 'create'])->name('products.import.view');
    Route::post('products/import/', [ProductImportController::class, 'store'])->name('products.import.store');
    Route::get('products/export/', [ProductExportController::class, 'create'])->name('products.export.store');
    Route::resource('/products', ProductController::class);
});
    // Route POS
        //for theshelf products
        Route::get('shelf-products', ShelfProducts::class)->name('shelf-products.index');
        //pos
        Route::get('/pos', CreateOrder::class)->middleware('auth')->name('pos.index');

        Route::get('/invoices/create', [InvoiceController::class, 'create'])->middleware('auth')->name('invoices.create');
        Route::post('/orders', [OrderController::class, 'store'])->middleware('auth')->name('orders.store');
        Route::post('/pos/store-debt', [OrderController::class, 'storeDebt'])->middleware('auth')->name('pos.storeDebt');
        Route::get('/orders', [OrderController::class, 'index'])->middleware('auth')->name('orders.index');

    //Route::post('/pos/invoice', [PosController::class, 'createInvoice'])->name('pos.createInvoice');
    //Route::post('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
    ;

    // for the product locations know how many products in the location
    Route::get('/location-setup', LocationSetup::class)->name('location-setup');
    // for the setting the product locations
    Route::get('/locations', Locations::class)->name('locations.index');

Route::resource('orders', OrderController::class);
Route::resource('debts', DebtsController::class);


      // Route Orders
      Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
      Route::get('/orders/pending', OrderPendingController::class)->name('orders.pending');
      Route::get('/orders/complete', [OrderCompleteController::class, 'update'])->name('orders.complete');
      Route::post('/orders/{uuid}/approve', [OrderController::class, 'approve'])->name('orders.approve');
    // web.php
    Route::post('/set-active-customer', [OrderController::class, 'setActiveCustomer'])->name('setActiveCustomer');

    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    //Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');

    // SHOW ORDERRoute::get('/orders/pending', OrderPendingController::class)->name('orders.pending');
    Route::get('/orders/complete', OrderCompleteController::class)->name('orders.complete');
    Route::get('/orders/{order}', [PosController::class, 'showOrder'])->name('orders.show');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/update/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/cancel/{order}', [OrderController::class, 'cancel'])->name('orders.cancel');


    // TODO: Remove from OrderController
    Route::get('/orders/details/{order_id}/download', [OrderController::class, 'downloadInvoice'])->name('order.downloadInvoice');
    Route::get('/orders/customer/{customerId}/details', [OrderController::class, 'getCustomerOrderDetails'])
    ->name('orders.customer.details');
    Route::get('/orders/customer/{customerId}', [OrderController::class, 'getCustomerOrders']);
    // routes for oders
    Route::get('/orders/customer/{customerId}/details', [OrderController::class, 'getCustomerOrderDetails'])
    ->name('orders.customer.details');

    Route::get('/pos/get-cart-content', [OrderController::class, 'getCartContent'])->name('pos.getCartContent');
    Route::post('/cart/add/{productId}', [OrderController::class, 'addToCart'])
    ->name('cart.add');
    //from Ajax but for oders
    Route::post('add-to-cart/{product}', [OrderController::class, 'addCartItem'])->name('addCartItem');
    Route::post('update-cart-item/{rowId}', [OrderController::class, 'updateCartItem'])->name('updateCartItem');
    Route::delete('delete-cart-item/{rowId}', [OrderController::class, 'deleteCartItem'])->name('deleteCartItem');


    // DUES
    Route::get('due/orders/', [DueOrderController::class, 'index'])->name('due.index');
    Route::get('due/order/view/{order}', [DueOrderController::class, 'show'])->name('due.show');
    Route::get('due/order/edit/{order}', [DueOrderController::class, 'edit'])->name('due.edit');
    Route::put('due/order/update/{order}', [DueOrderController::class, 'update'])->name('due.update');

    // Route Purchases
    Route::get('/purchases/approved', [PurchaseController::class, 'approvedPurchases'])->name('purchases.approvedPurchases');
    Route::get('/purchases/report', [PurchaseController::class, 'purchaseReport'])->name('purchases.purchaseReport');
    Route::get('/purchases/report/export', [PurchaseController::class, 'getPurchaseReport'])->name('purchases.getPurchaseReport');
    Route::post('/purchases/report/export', [PurchaseController::class, 'exportPurchaseReport'])->name('purchases.exportPurchaseReport');

    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

    Route::get('/purchases/show/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('/purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::put('/purchases/{purchase}/update', [PurchaseController::class, 'update'])->name('purchases.update');

    Route::get('/suppliers/info/{uuid}', [SupplierController::class, 'getSupplierDetails'])->name('suppliers.details');


    Route::post('/purchases/{uuid}/approve', [PurchaseController::class, 'approve'])->name('purchases.approve');

    Route::delete('/purchases/delete/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.delete');

    // Route Quotations
    // Route::get('/quotations/{quotation}/edit', [QuotationController::class, 'edit'])->name('quotations.edit');
    Route::post('/quotations/complete/{quotation}', [QuotationController::class, 'update'])->name('quotations.update');
    Route::delete('/quotations/delete/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.delete');
});




// Route::post('/handle-payment', [PosController::class, 'handlePayment'])->name('invoices.create');

    //Route Finassist

Route::get('/finassist', [FinAssistController::class, 'index'])->name('finassist');
Route::post('/finassist/query', [FinAssistController::class, 'handleQuery'])->name('finassist.query');

// routes/web.php
Route::get('/debts', [DebtsController::class, 'index'])->name('debts.index');
Route::post('/debts', [DebtsController::class, 'store'])->name('debts.store');
Route::get('/debts/{uuid}/edit', [DebtsController::class, 'edit'])->name('debts.edit');
Route::put('/debts/{uuid}', [DebtsController::class, 'update'])->name('debts.update');
Route::delete('/debts/{uuid}', [DebtsController::class, 'destroy'])->name('debts.destroy');
Route::post('/debts/pay', [DebtsController::class, 'pay'])->name('debts.pay');
Route::get('/debts/{uuid}/payments', [DebtsController::class, 'showPaymentHistory'])->name('debts.history');

//route to game
Route::prefix('gamification')->middleware(['auth'])->group(function () {
    Route::get('/RsmPlay', [GamificationController::class, 'index'])->name('gamification.board');
    Route::post('/missions/{id}/complete', [GamificationController::class, 'completeMission'])->name('gamification.mission.complete');
    Route::post('/rewards/{id}/redeem', [GamificationController::class, 'redeemReward'])->name('gamification.reward.redeem');
});

// Stock Transfer
Route::get('/stock/transfer', [StockController::class, 'transfer'])->name('stock.transfer');Route::post('/stock/transfer', [StockController::class, 'transferStock'])->name('stock.transfer.post');
Route::delete('/stock-transfers/{id}', [StockController::class, 'destroy'])->name('stock.transfer.delete');
// Damaged Products
Route::get('/stock/damaged', [StockController::class, 'showDamagedForm'])->name('stock.damaged');
Route::post('/stock/damaged', [StockController::class, 'recordDamaged'])->name('stock.damaged.post');

//  Route for Expense and Budget
Route::middleware(['auth'])->group(function () {
    Route::resource('expenses', ExpenseController::class);
    Route::resource('budgets', BudgetController::class);
});

// Routes to report
Route::middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::post('/reports/generate-daily', [ReportController::class, 'generateDailyReport'])->name('reports.generate-daily');
    Route::patch('/recommendations/{id}/read', [ReportController::class, 'markRecommendationRead'])->name('recommendations.read');
    // routes/web.php
Route::post('/reports/calculate-balance-sheet', [ReportController::class, 'calculateBalanceSheet'])->name('reports.calculateBalanceSheet');
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
//routes for branch
Route::middleware(['auth'])->group(function () {
    Route::resource('branches', BranchController::class);
});

//route for terms&policy
Route::get('Terms&Policy', function () {
    return view('terms&policy.index');   // The  team   page
})->name('index.route');

// route to Onboarding
Route::get('/onboarding', [OnboardingController::class, 'showOnboarding'])->name('auth.onboarding');

//route  to verify email
// Group routes with shared middleware
// Route::middleware(['auth', 'throttle:6,1'])->group(function () {
//     // Verification notice page
//     Route::get('auth/email/verify', [EmailVerificationController::class, 'showVerificationForm'])
//         ->name('verification.notice');

//     // Resend verification email
//     Route::post('auth/email/verification-notification', [EmailVerificationController::class, 'sendVerification'])
//         ->name('verification.send');
// });

// // Route for token-based email verification (no authentication required)
// Route::get('auth/email/verify/{token}', [EmailVerificationController::class, 'verify'])
//     ->middleware(['guest', 'throttle:6,1']) // Ensure unauthenticated users only
//     ->name('verification.verify');
// see the supplier in the dashboard
Route::get('/purchases-by-supplier', [PurchaseController::class, 'getPurchasesBySupplier'])->name('purchases.bySupplier');
Route::get('/purchases-by-category', [PurchaseController::class, 'getPurchasesByCategory'])->name('purchases.byCategory');
//for notifications
use App\Http\Controllers\NotificationController;
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
});
//for expence cartegories
Route::post('/expense-categories', [ExpenseCategoryController::class, 'store'])->name('expense-categories.store');

//for expence cartegories
Route::post('/budget-categories', [BudgetCategoryController::class, 'store'])->name('budget-categories.store');

//translate


Route::post('/translate', [TranslationController::class, 'translate'])->name('translate');

Route::middleware(['auth'])->group(function () {
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions/assign', [SubscriptionController::class, 'assign'])->name('subscriptions.assign');
    Route::put('/subscriptions/{user}/update', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::delete('/subscriptions/{user}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    // web.php
Route::get('/subscriptions/{subscriptionId}/pay', [SubscriptionController::class, 'pay'])->name('subscriptions.pay');
Route::post('/subscriptions/{subscriptionId}/process-payment', [SubscriptionController::class, 'processPayment'])->name('subscriptions.process-payment');
});

//language


// Language switcher route
Route::get('/change-locale/{locale}', [LocaleController::class, 'change'])->name('change-locale');



Route::middleware(['auth'])->group(function () {
    Route::get('/ads-generator', [AdsGeneratorController::class, 'index'])->name('ads.generator');
    Route::post('/ads-generator/upload', [AdsGeneratorController::class, 'generateAd'])->name('ads.generator.upload');


});



 // Profile currency update route - fix the route to point to the correct controller
 Route::post('/profile/currency/update', [ProfileCurrencyController::class, 'currencyUpdate'])
 ->name('profile.currency.update');

// Route to manage liabilities

Route::middleware('auth')->group(function () {
    // Liability Management
    Route::get('/liabilities', [LiabilityController::class, 'index'])->name('liabilities.index');
    Route::post('/liabilities', [LiabilityController::class, 'store'])->name('liabilities.store');
    Route::delete('/liabilities/{liability}', [LiabilityController::class, 'destroy'])->name('liabilities.destroy');
    Route::post('/liabilities/{liability}/pay', [LiabilityController::class, 'makePayment'])->name('liabilities.pay');
    Route::get('/liabilities/{liability}/history', [LiabilityController::class, 'paymentHistory'])->name('liabilities.history');
    Route::delete('/liabilities/{liability}', [LiabilityController::class, 'destroy'])->name('liabilities.destroy');
    Route::post('/liabilities/consolidate', [LiabilityController::class, 'consolidateDebts'])->name('liabilities.consolidate');

    // Loan Calculator
    Route::get('/loan-calculator', [LiabilityController::class, 'loanCalculator'])->name('loan.calculator');
    Route::post('/calculate-loan', [LiabilityController::class, 'calculateLoan'])->name('calculate.loan');
});


//-------------THE ROUTES TO THE Roman Website place ,WELCOMES ----------------



Route::get('/About Us', function () {
    return view('front.habout');       // The About  page
})->name('habout.route');

Route::get('/Contact', function () {
    return view('front.contact');       // The Contact  page
})->name('contact.route');

Route::get('/OurPricing', function () {
    return view('front.price');       // The pricing   page
})->name('price.route');

Route::get('/OurServices', function () {
    return view('front.service');   // The services  page
})->name('service.route');

Route::get('/Our Team', function () {
    return view('front.team');   // The  team   page
})->name('team.route');


// the payments portal
/*
Route::get('/Payments', function () {
    return view('paymentsportal.payments');   // The  payments page
})->name('payments.route'); */


require __DIR__.'/auth.php';

Route::get('test/', function (){
    return view('test');
});
