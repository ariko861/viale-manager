<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;

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
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/reservations', function () {
    return view('reservations');
})->name('reservations');

Route::middleware(['auth:sanctum', 'verified'])->get('/visitors', function () {
    return view('visitors');
})->name('visitors');

Route::middleware(['auth:sanctum', 'verified'])->get('/rooms', function () {
    return view('rooms');
})->name('rooms');

Route::middleware(['auth:sanctum', 'verified'])->get('/configuration', function () {
    return view('configuration');
})->name('configuration');

Route::middleware(['auth:sanctum', 'verified'])->get('/users-management', function () {
    return view('users-management');
})->name('users-management');

Route::middleware(['auth:sanctum', 'verified'])->get('/roles-management', function () {
    return view('roles-management');
})->name('roles-management');



//Route::resource('visitors', VisitorController::class)->name('visitors');
