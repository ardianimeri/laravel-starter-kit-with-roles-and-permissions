<?php

use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;

Route::name('admin.')->prefix('admin')->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {

        $userMiddleware = [
            'can:update users',
            'can:create users',
            'can:delete users',
        ];

        Route::get('users', [UsersController::class, 'index'])
            ->name('users.index')
            ->middleware('can:read users');

        Route::resource('users', UsersController::class)
            ->except(['index', 'show'])
            ->middleware($userMiddleware);

        $permissionMiddleware = [
            'can:update permissions',
            'can:create permissions',
            'can:delete permissions',
            'can:read permissions',
        ];

        Route::resource('permissions', PermissionsController::class)
            ->only(['index', 'store', 'destroy'])
            ->middleware($permissionMiddleware);

        $roleMiddleware = [
            'can:update roles',
            'can:create roles',
            'can:delete roles',
            'can:read roles',
        ];

        Route::resource('roles', RolesController::class)
            ->except(['show'])
            ->middleware($roleMiddleware);
    });
});
