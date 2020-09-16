<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CustomerController;

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

/*
|--------------------------------------------------------------------------
| Book Database Accessors
|--------------------------------------------------------------------------
*/

Route::post('/books',[BookController::class, 'store']);
Route::patch('/books/{book}', [BookController::class, 'update']);
Route::delete('/books/{book}', [BookController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| Customer Database Accessors
|--------------------------------------------------------------------------
*/

Route::post('/customers',[CustomerController::class, 'store']);
Route::patch('/customers/{customer}', [CustomerController::class, 'update']);
Route::delete('/customers/{customer}', [CustomerController::class, 'destroy']);

Route::post('customers/{customer}/books/{book}', [CustomerController::class, 'associate']);
Route::delete('customers/{customer}/books/{book}', [CustomerController::class, 'dissociate']);