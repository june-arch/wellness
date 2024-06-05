<?php

use App\Http\Controllers\Api\Auth\AdminAuthController;
use App\Http\Controllers\Api\Companies\CompanyController;
use App\Http\Controllers\Api\Companies\HealthThresholdController;
use App\Http\Controllers\Api\Notifications\NotificationController;
use App\Http\Controllers\Api\Reports\DashboardReportController;
use App\Http\Controllers\Api\Tasks\TaskCategoryController;
use App\Http\Controllers\Api\Tasks\TaskController;
use App\Http\Controllers\Api\Users\Admins\AdminController;
use App\Http\Controllers\Api\Users\Admins\AdminLogController;
use App\Http\Controllers\Api\Users\MemberController;
use App\Http\Controllers\Api\Users\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->middleware('guest')->group(function () {
    Route::post('password-reset', [AdminAuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login');
});

Route::middleware(['auth:admin', 'user:admin'])->group(function () {
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/company', [AdminAuthController::class, 'company'])->middleware('has.company')->name('company');
        Route::get('/', [AdminAuthController::class, 'profile']);
        Route::put('/', [AdminAuthController::class, 'updateProfile'])->name('update-profile');
    });

    Route::prefix('report')->name('report.')->group(function () {
        Route::get('dashboard', [DashboardReportController::class, 'index'])->name('dashboard');
        Route::post('detailFitness', [DashboardReportController::class, 'detailFitness'])->name('detailFitness');
        Route::post('performance', [DashboardReportController::class, 'performance'])->name('performance');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/log', [AdminLogController::class, 'index'])->middleware('permissions:read-log')->name('log');

        Route::get('{admin}', [AdminController::class, 'show'])->middleware('permissions:read-admin')->name('show');
        Route::put('{admin}', [AdminController::class, 'update'])->middleware('permissions:update-admin')->name('update');
        Route::post('/', [AdminController::class, 'store'])->middleware('permissions:create-admin')->name('store');
        Route::get('/', [AdminController::class, 'index'])->name('index');
    });

    Route::prefix('member')->name('member.')->group(function () {
        Route::get('{member}/healths', [MemberController::class, 'healths'])->middleware('permissions:read-health')->name('healths');

        Route::get('{member}', [MemberController::class, 'show'])->middleware('permissions:read-member')->name('show');
        Route::put('{member}', [MemberController::class, 'update'])->middleware('permissions:update-member')->name('update');
        Route::post('/', [MemberController::class, 'store'])->middleware('permissions:create-member')->name('store');
        Route::get('/', [MemberController::class, 'index'])->middleware('permissions:read-member')->name('index');
    });

    Route::prefix('company')->name('company.')->group(function () {
        Route::get('{company}', [CompanyController::class, 'show'])->middleware('permissions:read-company')->name('show');
        Route::put('{company}', [CompanyController::class, 'update'])->middleware('permissions:update-company')->name('update');
        Route::post('/', [CompanyController::class, 'store'])->middleware('permissions:create-company')->name('store');
        Route::get('/', [CompanyController::class, 'index'])->middleware('permissions:read-company')->name('index');
    });

    Route::prefix('company-threshold')->name('company-threshold.')->group(function () {
        Route::get('types', [HealthThresholdController::class, 'typeList'])->name('typeList');

        Route::get('{health_threshold}', [HealthThresholdController::class, 'show'])->middleware('permissions:read-company')->name('show');
        Route::put('{health_threshold}', [HealthThresholdController::class, 'update'])->middleware('permissions:update-company')->name('update');
        Route::post('/', [HealthThresholdController::class, 'store'])->middleware('permissions:update-company')->name('store');
        Route::get('/', [HealthThresholdController::class, 'index'])->middleware('permissions:read-company')->name('index');
    });

    Route::prefix('role')->name('role.')->group(function () {
        Route::get('{role}', [RoleController::class, 'show'])->middleware('permissions:read-role')->name('show');
        // Route::put('{role}', [RoleController::class, 'update'])->middleware('permissions:update-role')->name('update');
        // Route::post('/', [RoleController::class, 'store'])->middleware('permissions:create-role')->name('store');
        Route::get('/', [RoleController::class, 'index'])->middleware('permissions:read-role')->name('index');
    });

    Route::prefix('notification')->name('notification.')->group(function () {
        Route::put('{notification}/read', [NotificationController::class, 'read'])->name('read');
        Route::put('{notification}/unread', [NotificationController::class, 'unread'])->name('unread');
        Route::put('mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::get('/', [NotificationController::class, 'index'])->name('index');
    });

    Route::prefix('task')->name('task.')->group(function () {
        Route::prefix('category')->name('category.')->group(function () {
            Route::get('{task_category}', [TaskCategoryController::class, 'show'])
                ->middleware('permissions:read-task-category')->name('show');
            Route::put('{task_category}', [TaskCategoryController::class, 'update'])
                ->middleware('permissions:update-task-category')->name('update');
            Route::post('/', [TaskCategoryController::class, 'store'])
                ->middleware('permissions:create-task-category')->name('store');
            Route::get('/', [TaskCategoryController::class, 'index'])
                ->middleware('permissions:read-task-category')->name('index');
        });

        Route::prefix('tag')->name('tag.')->group(function () {
            Route::get('{task_tag}', [TaskCategoryController::class, 'show'])
                ->middleware('permissions:read-task-tag')->name('show');
            Route::put('{task_tag}', [TaskCategoryController::class, 'update'])
                ->middleware('permissions:update-task-tag')->name('update');
            Route::post('/', [TaskCategoryController::class, 'store'])
                ->middleware('permissions:create-task-tag')->name('store');
            Route::get('/', [TaskCategoryController::class, 'index'])
                ->middleware('permissions:read-task-tag')->name('index');
        });

        Route::get('{task}', [TaskController::class, 'show'])->middleware('permissions:read-task')->name('show');
        Route::put('{task}', [TaskController::class, 'update'])->middleware('permissions:update-task')->name('update');
        Route::post('/', [TaskController::class, 'store'])->middleware('permissions:create-task')->name('store');
        Route::get('/', [TaskController::class, 'index'])->middleware('permissions:read-task')->name('index');
    });
});
