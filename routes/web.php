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
    Route::get('/', 'App\Http\Controllers\BillController@index')->name('index');
    Route::get('/accept', 'App\Http\Controllers\BillController@accept')->name('accept');
    Route::get('/accepted', 'App\Http\Controllers\BillController@accepted')->name('accepted');
    Route::get('/my', 'App\Http\Controllers\BillController@my')->name('my');
    Route::get('/form', 'App\Http\Controllers\BillController@form')->name('form');
    Route::post('/store', 'App\Http\Controllers\BillController@store')->name('store');
    Route::get('/{bill}', 'App\Http\Controllers\BillController@view')->name('view');
    Route::get('consult/{bill}', 'App\Http\Controllers\BillController@consult')->name('consult');
});
Route::middleware(['auth:sanctum', 'verified'])->group(function (){

});
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return redirect()->route('bill.index');
})->name('dashboard');
