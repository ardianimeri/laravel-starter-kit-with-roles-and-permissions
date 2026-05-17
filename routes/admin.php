<?php

use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;

Route::name('admin.')->prefix('admin')->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('users', [UsersController::class, 'index'])->name('users.index')->middleware('can:read-users');
        Route::get('users/create', [UsersController::class, 'create'])->name('users.create')->middleware('can:create-users');
        Route::post('users', [UsersController::class, 'store'])->name('users.store')->middleware('can:create-users');
        Route::get('users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit')->middleware('can:update-users');
        Route::put('users/{user}', [UsersController::class, 'update'])->name('users.update')->middleware('can:update-users');
        Route::delete('users/{user}', [UsersController::class, 'destroy'])->name('users.destroy')->middleware('can:delete-users');

        Route::get('roles', [RolesController::class, 'index'])->name('roles.index')->middleware('can:read-roles');
        Route::get('roles/create', [RolesController::class, 'create'])->name('roles.create')->middleware('can:create-roles');
        Route::post('roles', [RolesController::class, 'store'])->name('roles.store')->middleware('can:create-roles');
        Route::get('roles/{role}/edit', [RolesController::class, 'edit'])->name('roles.edit')->middleware('can:update-roles');
        Route::put('roles/{role}', [RolesController::class, 'update'])->name('roles.update')->middleware('can:update-roles');
        Route::delete('roles/{role}', [RolesController::class, 'destroy'])->name('roles.destroy')->middleware('can:delete-roles');

        Route::get('permissions', [PermissionsController::class, 'index'])->name('permissions.index')->middleware('can:read-permissions');
        Route::post('permissions', [PermissionsController::class, 'store'])->name('permissions.store')->middleware('can:create-permissions');
        Route::delete('permissions/{permission}', [PermissionsController::class, 'destroy'])->name('permissions.destroy')->middleware('can:delete-permissions');

    });
});
