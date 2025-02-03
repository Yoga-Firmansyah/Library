<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [AdminController::class, 'index']);

    Route::resource('/authors', AuthorController::class);
    Route::resource('/books', BookController::class);
    Route::resource('/catalogs', CatalogController::class);
    Route::resource('/members', MemberController::class);
    Route::resource('/publishers', PublisherController::class);
    Route::resource('/transactions', TransactionController::class);

    Route::get('/api/authors', [AuthorController::class, 'api']);
    Route::get('/api/publishers', [PublisherController::class, 'api']);
    Route::get('/api/members', [MemberController::class, 'api']);
    Route::get('/api/books', [BookController::class, 'api']);
    Route::get('/api/transactions', [TransactionController::class, 'api']);

    Route::get('/test_spatie', [AdminController::class, 'test_spatie']);
});
