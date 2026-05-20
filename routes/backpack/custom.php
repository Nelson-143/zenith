<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserCrudController;
use App\Http\Controllers\MaintenanceController;
// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix'), // Will use 'chiefs/onebyone'
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),

    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user-crud-controller', 'UserCrudControllerCrudController');
    Route::crud('admin', 'AdminCrudController');
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('user/locations', [UserCrudController::class, 'showLocations'])->name('user.locations');
    Route::crud('user', 'UserCrudController');
    Route::post('toggle-maintenance', '\App\Http\Controllers\MaintenanceController@toggle')
    ->name('backpack.toggle-maintenance');

// Dashboard Route
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Clear Cache Route
Route::get('admin/clear-cache', [DashboardController::class, 'clearCache'])->name('admin.clear.cache');

// Block User Route
Route::get('admin/block-user/{id}', [DashboardController::class, 'blockUser '])->name('admin.block.user');

// Ban IP Route
Route::get('admin/ban-ip/{ip}', [DashboardController::class, 'banIp'])->name('admin.ban.ip');
    Route::crud('financial-dashboard', 'FinancialDashboardCrudController');


    // Custom user routes
Route::get('custom-users', 'App\Http\Controllers\Admin\CustomUserController@index');
Route::get('custom-users/{uuid}', 'App\Http\Controllers\Admin\CustomUserController@show');
}); // this should be the absolute last line of this file



/**
 * DO NOT ADD ANYTHING HERE.
 */
