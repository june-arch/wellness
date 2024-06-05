<?php

use App\Http\Controllers\MemberApi\Auth\MemberAuthController;
use App\Http\Controllers\MemberApi\Auth\MemberTokenController;
use App\Http\Controllers\MemberApi\Healths\MemberHealthController;
use App\Http\Controllers\MemberApi\Masters\MasterController;
use App\Http\Controllers\MemberApi\Notifications\MemberNotificationController;
use App\Http\Controllers\MemberApi\PSS\PSSController;
use App\Http\Controllers\MemberApi\Tasks\MemberTaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('login', [MemberAuthController::class, 'login'])->name('login');
    Route::post('password-reset', [MemberAuthController::class, 'resetPassword'])->name('password.reset');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('logout', [MemberAuthController::class, 'logout'])->name('logout');
        Route::delete('token/{id}', [MemberTokenController::class, 'removeToken'])->name('removeToken');
        Route::get('token', [MemberTokenController::class, 'tokens'])->name('token');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [MemberAuthController::class, 'profile']);
        Route::put('/', [MemberAuthController::class, 'updateProfile'])->name('update');
    });

    Route::prefix('report')->name('report.')->group(function () {
        /**
        TODO:
        - Report untuk dashboard utama
        - Report untuk setiap item Health: bisa dipilih daily, weekly, monthly
         */
    });

    Route::prefix('health')->name('health.')->group(function () {
        Route::get('/codes', [MemberHealthController::class, 'healthCodes'])->name('codes');
        Route::get('/today', [MemberHealthController::class, 'today'])->name('today');
        Route::post('/', [MemberHealthController::class, 'store'])->name('store');
        Route::post('/score', [MemberHealthController::class, 'score'])->name('score');
        Route::get('/', [MemberHealthController::class, 'index']);
    });

    Route::prefix('master')->name('master.')->group(function () {
        Route::prefix('{admin}')->group(function () {
            Route::prefix('chat')->name('chat.')->group(function () {
                Route::post('/', [MasterController::class, 'sendChat'])->name('send');
                Route::get('/', [MasterController::class, 'chat']);
            });

            Route::get('/', [MasterController::class, 'show'])->name('show');
        });

        Route::get('/', [MasterController::class, 'index']);
    });

    Route::prefix('task')->name('task.')->group(function () {
        Route::get('/history', [MemberTaskController::class, 'history'])->name('history');
        Route::put('{user_task}/uncomplete', [MemberTaskController::class, 'uncomplete'])->name('uncomplete');
        Route::put('{user_task}/complete', [MemberTaskController::class, 'complete'])->name('complete');
        Route::get('{user_task}', [MemberTaskController::class, 'show'])->name('show');
        Route::get('/', [MemberTaskController::class, 'index']);
    });

    Route::prefix('notification')->name('notification.')->group(function () {
        Route::put('{notification}/read', [MemberNotificationController::class, 'read'])->name('read');
        Route::put('{notification}/unread', [MemberNotificationController::class, 'unread'])->name('unread');
        Route::put('mark-all-as-read', [MemberNotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::get('/', [MemberNotificationController::class, 'index'])->name('index');
    });

    Route::prefix('pss')->name('pss.')->group(function () {
        Route::get('questions', [PSSController::class, 'questions'])->name('questions');
        Route::get('answers', [PSSController::class, 'answers'])->name('answers');
        Route::post('submit', [PSSController::class, 'submit'])->name('submit');
    });
});
