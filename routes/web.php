<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
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

Route::get('/', [ListingController::class, 'index']);

// show the form to create a new listing
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');

// store a new listing
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

// show the form for editing a listing
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');

// update a listing
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

// delete a listing
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

// show all users listings
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

// Must always be last for listings resource
Route::get('/listings/{listing}', [ListingController::class, 'show']);

// show register create form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

// create new user
Route::post('/users', [UserController::class, 'store']);

//  logout user
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// show login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// login user
Route::post('/users/authenticate', [UserController::class, 'authenticate']);