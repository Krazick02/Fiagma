<?php

// use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\PersonController;
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
    
    Route::post('/user',[PersonController::class,'store'])->name('users.store');

    Route::get('/home',function(){
        return view('home');
    })->name('fiagma.home');
});

Route::get('/', function(){
    return view('welcome');
});

// Route::get('/login', function(){
//     return view('fiagma.login');
// })->name('login');

Route::get('/register', function(){
    return view('fiagma.register');
})->name('register');