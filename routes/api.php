<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentsController;
use App\Http\Controllers\Api\SubscriptionTypeController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\BookSubscriptionReportController;

use App\Http\Controllers\Api\BookController;

Route::prefix('v1')->group(function () {

    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('signup', [AuthController::class, 'signup']);
        Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        Route::post('create-password', [AuthController::class, 'createPassword']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
        Route::put('profile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');
    });

    // Students API routes
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentsController::class, 'index']);
        Route::post('/create', [StudentsController::class, 'store']);
        Route::get('{student}', [StudentsController::class, 'show']);
        Route::put('{student}', [StudentsController::class, 'update']);
        Route::patch('{student}', [StudentsController::class, 'update']);
        Route::delete('{student}', [StudentsController::class, 'destroy']);
        Route::get('export/csv', [StudentsController::class, 'exportCsv']);
        Route::get('export/excel', [StudentsController::class, 'exportExcel']);
    });

    // 📚 Books API routes
    Route::apiResource('books', BookController::class);
    Route::apiResource('subscription-types', SubscriptionTypeController::class);
    Route::apiResource('vendors', VendorController::class);
    Route::apiResource('roles', RoleController::class);
    Route::get('profile', [ProfileController::class, 'index']);
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/send-sms', [NotificationController::class, 'sendSms']);
    Route::get('book-subscription-report', [BookSubscriptionReportController::class, 'index']);

});
