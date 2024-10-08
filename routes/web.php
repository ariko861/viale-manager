<?php

use App\Http\Controllers\TransportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\RecapController;
use App\Http\Controllers\LookingForPlace;
use App\Models\UserInvite;
use App\Models\Option;
use App\Models\MatrixLink;
use Illuminate\Auth\Middleware\Authorize;
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
    $options = Option::all();
    return view('welcome', ['options' => $options]);
})->name('welcome');

Route::post('/', [LookingForPlace::class, 'lookForPlace'])->name('transport');

Route::get('/matrix/{link?}', function($link = null) {
    if ( $link )
    {
        $matrix = MatrixLink::where('link', $link)->get()->first();
        if ($matrix) return view('matrix', ['matrix' => $matrix ]);
        else return redirect('/');
    } else {
        return redirect('/');
    }
})->name('matrix');

Route::middleware('hasUserInvitation')->get('/register', [ RegisterController::class, 'showRegistrationForm'])->name('register');

Route::middleware('confirmationLinkIsValid')->get('/confirmation', [ ConfirmationController::class, 'showConfirmationForm'])->name('confirmation');

Route::get('/confirmation/{link_token}', [ ConfirmationController::class, 'newShowConfirmationForm'])->name('new-confirmation');

Route::middleware('transportLinkIsValid')->get('/transports-disponibles', [ TransportController::class, 'showTransports'])->name('transports-disponibles');

Route::middleware(['auth:web', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');



    Route::middleware('can:reservation-list')->get('/reservations/{id?}', function ($reservation_id = null) {
        return view('reservations', ['reservation_id' => $reservation_id]);
    })->name('reservations');

    Route::middleware('can:reservation-list')->get('/transports', function () {
        return view('transports');
    })->name('transports');

    Route::middleware('can:reservation-list')->get('/recapitulatif/reservations/{beginDate}/{endDate}', [RecapController::class, 'printReservationRecap'])->name('recapitulatif');

    Route::middleware('can:visitor-list')->get('/visitors', function () {
        return view('visitors');
    })->name('visitors');

    Route::middleware('can:room-list')->get('/rooms', function () {
        return view('rooms');
    })->name('rooms');

    Route::middleware('can:config-manage')->get('/configuration', function () {
        return view('configuration');
    })->name('configuration');

    Route::middleware('can:statistics-read')->get('/statistics', function () {
        return view('statistics');
    })->name('statistics');

    Route::middleware('can:user-manage')->get('/users-management', function () {
        return view('users-management');
    })->name('users-management');

    Route::middleware('can:community-list')->get('/communities', function () {
        return view('communities');
    })->name('communities');

    Route::middleware('can:role-manage')->get('/roles-management', function () {
        return view('roles-management');
    })->name('roles-management');


});




//Route::resource('visitors', VisitorController::class)->name('visitors');
