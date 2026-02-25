<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('products/', [ProductController::class, 'index'])->name('api.product.index');



// use App\Http\Controllers\Auth\EmailVerificationController;
// Route::prefix('auth')->group(function () {
//     Route::post('/email/verify/send', [EmailVerificationController::class, 'sendVerification']);
//     Route::get('/email/verify/{token}', [EmailVerificationController::class, 'verify']);
//     Route::post('/email/verify/resend', [EmailVerificationController::class, 'resendVerification']);
// });

