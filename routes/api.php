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
use App\Http\Controllers\Api\LibraryController;
use App\Http\Controllers\Api\HallController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\ProfileeController;
use App\Http\Controllers\Api\DashboardController;



Route::prefix('v1')->group(function () {

    // ================= AUTH ROUTES =================
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

    // ================= STUDENTS ROUTES =================
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


    // ================= LIBRARIES ROUTES (Auth Required) =================
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('libraries', [LibraryController::class, 'store']);
        Route::get('completion-status', [LibraryController::class, 'completionStatus']); // 👈 new route
    });


    Route::middleware('auth:sanctum')->get('/dashboard', [DashboardController::class, 'home']);

    // ================= BOOKS ROUTES =================
    Route::apiResource('books', BookController::class);

    // ================= SUBSCRIPTION TYPES ROUTES =================
    Route::apiResource('subscription-types', SubscriptionTypeController::class);

    // ================= HALL ROUTES (Auth Required) =================
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('halls', HallController::class);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('plans', [PlanController::class, 'index']);
        Route::post('plans', [PlanController::class, 'store']);
    });


    // ================= VENDOR ROUTES =================
    Route::prefix('vendors')->group(function () {
        Route::post('login', [VendorController::class, 'login']);
        Route::post('logout', [VendorController::class, 'logout'])->middleware('auth:sanctum');
    });
    Route::apiResource('vendors', VendorController::class);

    // ================= ROLES ROUTES =================
    Route::apiResource('roles', RoleController::class);

    // ================= PROFILE ROUTES =================
    Route::get('profile', [ProfileController::class, 'index']);

    // ================= NOTIFICATION ROUTES =================
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/send-sms', [NotificationController::class, 'sendSms']);

    // ================= BOOK SUBSCRIPTION REPORT ROUTES =================
    Route::get('book-subscription-report', [BookSubscriptionReportController::class, 'index']);
});
Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->get('profile/completion', [ProfileeController::class, 'completion']);
});