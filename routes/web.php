<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BookSubscriptionReportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SubscriptionTypeController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\ProfileController;

// ✅ Root URL direct login page show kare
Route::get('/', [AdminController::class, 'showLoginForm'])->name('root.login');

// Main Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Redirect '/admin' to login
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });

    // Guest routes (login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminController::class, 'login'])->name('login.submit');
    });

    // Authenticated routes
    Route::middleware(['auth:admin', 'admin'])->group(function () {

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Admin Profile
        Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

        // Student Management Routes
        Route::resource('students', StudentsController::class);
        Route::get('students/export/csv', [StudentsController::class, 'exportCsv'])->name('students.export.csv');
        Route::get('students/export/excel', [StudentsController::class, 'exportExcel'])->name('students.export.excel');

        // Books Management Routes
        Route::resource('books', BookController::class);

        // Vendor Management Routes
       Route::get('vendors/{vendor}/edit-json', [VendorController::class, 'editJson'])->name('vendors.edit-json');
Route::resource('vendors', VendorController::class);


        // Reports Routes
        Route::get('report/book-subscriptions', [BookSubscriptionReportController::class, 'index'])->name('reports.book_subscription_dashboard');

        // Subscription Type Management Routes
        Route::get('subscription_types/{subscription_type}/edit-json', [SubscriptionTypeController::class, 'edit'])->name('subscription_types.edit-json');
        Route::resource('subscription_types', SubscriptionTypeController::class);
        Route::get('subscription_types/export/csv', [SubscriptionTypeController::class, 'exportCsv'])->name('subscription_types.export.csv');
        Route::get('subscription_types/export/excel', [SubscriptionTypeController::class, 'exportExcel'])->name('subscription_types.export.excel');

        // Notifications Management Routes
        Route::resource('notifications', NotificationController::class);
        Route::get('notifications/export/csv', [NotificationController::class, 'exportCsv'])->name('notifications.export.csv');
        Route::get('notifications/export/excel', [NotificationController::class, 'exportExcel'])->name('notifications.export.excel');

        // Roles Management
        Route::resource('roles', RoleController::class);

        // Logout Route
        Route::post('logout', [AdminController::class, 'logout'])->name('logout');
    });
});
