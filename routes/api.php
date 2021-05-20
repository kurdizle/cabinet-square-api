<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
});

Route::prefix('notes')->group(function () {
    Route::get('/', [NoteController::class, 'index'])->name('notes.index');
    Route::get('/{id}', [NoteController::class, 'show'])->name('notes.show');
    Route::post('/', [NoteController::class, 'store'])->name('notes.store');
    Route::put('/{id}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/{id}', [NoteController::class, 'destroy'])->name('notes.destroy');
});
