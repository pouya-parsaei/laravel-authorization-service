<?php


use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [IndexController::class, 'index'])->name('home');

Route::prefix('auth')->name('auth.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('register', [RegisterController::class, 'create'])->name('register-form');
        Route::post('register', [RegisterController::class, 'store'])->name('register');

        Route::get('login', [LoginController::class, 'create'])->name('login-form');
        Route::post('login', [LoginController::class, 'store'])->name('login');
    });

    Route::middleware('auth')->group(function () {
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    });

});

Route::prefix('panel')->middleware(['auth','can:show panel'])->group(function(){
    Route::resources([
        'users' => UserController::class,
    ]);

    Route::resources([
        'roles' => RoleController::class,
    ]);
});
