<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RecorderController;
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
Route::permanentRedirect('/', '/users');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{user}/policies', [PolicyController::class, 'index'])->name('user.policies');
Route::post('/users/{user}/policies', [PolicyController::class, 'store'])->name('user.policies.store');
Route::get('/users/{user}/policies/{policy}', [PolicyController::class, 'show'])->name('user.policy');
Route::delete('/policies/{policy}', [PolicyController::class, 'destroy'])->name('policy.destroy');
Route::get('/users/{user}/actors', [ActorController::class, 'index'])->name('user.actors');
Route::post('/users/{user}/actors', [ActorController::class, 'store'])->name('actor.store');
Route::get('/users/{user}/otherActors', [ActorController::class, 'other'])->name('user.actors.other');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/actors', [ActorController::class, 'index'])->name('actors.index');
Route::delete('/actors/{actor}', [ActorController::class, 'destroy'])->name('actor.destroy');
Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
Route::get('/users/{user}/recording', [RecorderController::class, 'index'])->name('user.recording');
Route::post('/users/{user}/recording', [RecorderController::class, 'store'])->name('user.recording.store');
Route::get('/users/{user}/voice-assistant', [PolicyController::class, 'assistant'])->name('user.assistant');

Route::get('/users/{user}/questions', [QuestionController::class, 'index'])->name('questions.index');
Route::post('/users/{user}/questions', [PolicyController::class, 'storeFromQuestions'])->name('user.policies.storeMultiple');
Route::post('/users/{user}/question', [PolicyController::class, 'storeFromQuestion'])->name('user.policies.storeSingle');

Route::get('/actions', ActionController::class);
