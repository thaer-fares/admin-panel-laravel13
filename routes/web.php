<?php

use App\Http\Controllers\UsersExportController;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Notifications\NotificationsIndex;
use App\Livewire\Permissions\Index as PermissionsIndex;
use App\Livewire\Profile\ChangePassword;
use App\Livewire\Profile\UpdateProfile;
use App\Livewire\Roles\Index as RolesIndex;
use App\Livewire\Users\Index as UsersIndex;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// تبديل اللغة (لا يحتاج تسجيل دخول)
Route::get('/lang/{locale}', function (string $locale) {
    $locale = in_array($locale, ['ar', 'en']) ? $locale : 'ar';
    session(['locale' => $locale]);

    if (Auth::check()) {
        Auth::user()->update(['locale' => $locale]);
    }

    return redirect()->back();
})->name('lang.switch');

// صفحة الدخول
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

// تسجيل الخروج
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');

// الصفحات المحمية بتسجيل الدخول
Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/users', UsersIndex::class)
        ->middleware('permission:manage-users')
        ->name('users.index');

    Route::get('/users/export/{format}', UsersExportController::class)
        ->middleware('permission:manage-users')
        ->whereIn('format', ['xlsx', 'csv', 'pdf'])
        ->name('users.export');

    Route::get('/roles', RolesIndex::class)
        ->middleware('permission:manage-roles')
        ->name('roles.index');

    Route::get('/permissions', PermissionsIndex::class)
        ->middleware('permission:manage-permissions')
        ->name('permissions.index');

    Route::get('/notifications', NotificationsIndex::class)->name('notifications.index');
    Route::get('/profile', UpdateProfile::class)->name('profile.edit');
    Route::get('/password', ChangePassword::class)->name('password.edit');
});
