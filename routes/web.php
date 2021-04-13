<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('users/{user}/policies', [PolicyController::class, 'index'])->name('user.policies');
Route::post('users/{user}/policies', [PolicyController::class, 'store'])->name('user.policies.store');
Route::get('/users/{user}/policies/{policy}', [PolicyController::class, 'show'])->name('user.policy');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/actors', [ActorController::class, 'index'])->name('actors.index');
Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
