<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', function () {
        return view('pages.welcome');
    })->name('welcome');

    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.submit');

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'register'])
        ->name('register.submit');
});

Route::group(['middleware' => 'auth'], function () {
    Route::view('/home', 'pages.dashboard')
        ->name('home');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    Route::get('/api/users', [UsersController::class, 'getUsers']);
    Route::post('/unarchive/{user}', [UsersController::class, 'unarchiveUser'])
        ->name('user.unarchive')
        ->withTrashed();
    Route::resource('users', UsersController::class)
        ->withTrashed(['destroy']);

    Route::get('/api/roles', [RolesController::class, 'getRoles']);
    Route::get('/api/roles/users', [RolesController::class, 'getUsers']);
    Route::get('/api/roles/users/{user}', [RolesController::class, 'showUserRoles']);
    Route::put('/api/roles/assign', [RolesController::class, 'assignRoles']);
    Route::get('/assign/roles', [RolesController::class, 'assignPage']);
    Route::resource('roles', RolesController::class);

    Route::get('/api/permissions', [PermissionsController::class, 'getPermissions']);
    Route::get('/api/permissions/users', [PermissionsController::class, 'getUsers']);
    Route::get('/api/permissions/users/{user}', [PermissionsController::class, 'showUserPermissions']);
    Route::put('/api/permissions/assign', [PermissionsController::class, 'assignPermissions']);
    Route::get('/assign/permissions', [PermissionsController::class, 'assignPage']);
    Route::resource('permissions', PermissionsController::class);

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [NotificationController::class, 'getNotifications']);
        Route::get('/unread', [NotificationController::class, 'getUnreadNotificationsCount']);
        Route::post('/markAsReadAll', [NotificationController::class, 'markAsReadAll']);
        Route::post('/markAsRead', [NotificationController::class, 'markAsRead']);
        Route::post('/markAsUnread', [NotificationController::class, 'markAsUnread']);
        Route::delete('/delete', [NotificationController::class, 'delete']);
    });
});
