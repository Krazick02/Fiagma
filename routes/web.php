<?php

use App\Http\Controllers\Web\UserController;
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
Route::middleware(['auth'])->group(function () {
    //users
    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/get/{id}', [UserController::class, 'get'])->name('users.get');
    Route::put('users', [UserController::class, 'update'])->name('users.edit');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::get('/', function(){
    return view('welcome');
});
