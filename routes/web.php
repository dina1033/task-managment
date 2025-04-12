<?php
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Middleware\EnsureUserIsUser;

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login-action', [LoginController::class, 'login'])->name('login-action');

Route::post('/logout', LogoutController::class)->name('logout');

Route::middleware(['auth:sanctum',EnsureUserIsUser::class])->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('dashboard');
    Route::resource('tasks', TaskController::class)->except(['show']);
    Route::post('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
});
