<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('/bill')->name('bill.')->middleware('auth')->group(function () {
    Route::get('/consult', 'App\Http\Controllers\BillController@consult')->name('consult');
    Route::get('/back/{bill}', 'App\Http\Controllers\BillController@back')->name('back');
    Route::get('/', 'App\Http\Controllers\BillController@index')->name('index');
    Route::get('/accept', 'App\Http\Controllers\BillController@accept')->name('accept');
    Route::get('/accepted', 'App\Http\Controllers\BillController@accepted')->name('accepted');
    Route::get('/my', 'App\Http\Controllers\BillController@my')->name('my');
    Route::get('/form', 'App\Http\Controllers\BillController@form')->name('form');
    Route::get('/delete/{bill}', 'App\Http\Controllers\BillController@delete')->name('delete');
    Route::post('/store', 'App\Http\Controllers\BillController@store')->name('store');
    Route::get('/{bill}', 'App\Http\Controllers\BillController@view')->name('view');
    Route::get('print/{bill}', 'App\Http\Controllers\BillController@printBill')->name('print');
});
Route::prefix('/chains')->name('chains.')->middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\ChainController@index')->name('index');
    Route::get('/form/{id?}', 'App\Http\Controllers\ChainController@form')->name('form');
});
Route::prefix('/organisations')->name('organisations.')->middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\OrganisationController@index')->name('index');
    Route::get('/form/{organisation?}', 'App\Http\Controllers\OrganisationController@form')->name('form');
});
Route::prefix('/users')->name('users.')->middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\UserController@index')->name('index');
    Route::get('/form/{user?}', 'App\Http\Controllers\UserController@form')->name('form');
});
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return redirect()->route('bill.index');
})->name('dashboard');
