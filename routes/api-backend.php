<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\bapi\AuthController;
use App\Http\Controllers\user\bapi\RolesController;
use App\Http\Controllers\customer\bapi\CustomerCategoryController;
use App\Http\Controllers\customer\bapi\CustomerController;
use App\Http\Controllers\notification\bapi\NotificationController;

Route::group(['prefix' => 'user', 'middleware' => ['guest:backend']], function () {
    Route::post('login', [AuthController::class, 'login'])->name('apiBackend.user.login');
    Route::get('401', [AuthController::class, 'unauthenticated'])->name('apiBackend.user.unauthenticated');
});

Route::group(['middleware' => ['auth:backend']], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::get('profile', [AuthController::class, 'profile'])->name('apiBackend.user.login');
        Route::put('profile-update', [AuthController::class, 'profile_update'])->name('apiBackend.user.profile_update');
        Route::post('profile-change-password', [AuthController::class, 'profile_change_password'])->name('apiBackend.user.profile_change_password');
        Route::post('logout', [AuthController::class, 'logout'])->name('apiBackend.user.login');
        Route::post('reset-password/{id}', [AuthController::class, 'reset_password'])->name('apiBackend.user.reset_password');
        Route::post('create', [AuthController::class, 'create'])->name('apiBackend.user.create');
        Route::put('update/{id}', [AuthController::class, 'update'])->name('apiBackend.user.update');
        Route::delete('delete/{id}', [AuthController::class, 'delete'])->name('apiBackend.user.delete');
    });
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/permissions', [RolesController::class, 'permissions'])->name('apiBackend.roles.permissions');
        Route::get('/index', [RolesController::class, 'index'])->name('apiBackend.roles.index');
        Route::post('/create', [RolesController::class, 'create'])->name('apiBackend.roles.create');
        Route::put('/update/{id}', [RolesController::class, 'update'])->name('apiBackend.roles.update');
        Route::delete('/delete/{id}', [RolesController::class, 'delete'])->name('apiBackend.roles.delete');
    });
    Route::group(['prefix' => 'customer-categories'], function () {
        Route::get('/index', [CustomerCategoryController::class, 'index'])->name('apiBackend.customer_categories.index');
        Route::post('/create', [CustomerCategoryController::class, 'create'])->name('apiBackend.customer_categories.create');
        Route::put('/update/{id}', [CustomerCategoryController::class, 'update'])->name('apiBackend.customer_categories.update');
        Route::delete('/delete/{id}', [CustomerCategoryController::class, 'delete'])->name('apiBackend.customer_categories.delete');
    });
    Route::group(['prefix' => 'customers'], function () {
        Route::get('/index', [CustomerController::class, 'index'])->name('apiBackend.customers.index');
        Route::post('/create', [CustomerController::class, 'create'])->name('apiBackend.customers.create');
        Route::put('/update/{id}', [CustomerController::class, 'update'])->name('apiBackend.customers.update');
        Route::delete('/delete/{id}', [CustomerController::class, 'delete'])->name('apiBackend.customers.delete');
    });
    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/search', [NotificationController::class, 'search'])->name('apiBackend.notifications.search');
        Route::get('/index', [NotificationController::class, 'index'])->name('apiBackend.notifications.index');
        Route::post('/create', [NotificationController::class, 'create'])->name('apiBackend.notifications.create');
        Route::get('/show/{id}', [NotificationController::class, 'show'])->name('apiBackend.notifications.show');
    });
});
