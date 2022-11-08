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
//dd('/'. env("TGBOT") .'/webhook');
Route::post('/'. env("TGBOT") .'/webhook', 'App\Http\Controllers\TelegramController@handle');
Route::get('/getMe', 'App\Http\Controllers\TelegramController@getMe');
Route::get('/send', 'App\Http\Controllers\TelegramController@send');
Route::get('/setWebHook', 'App\Http\Controllers\TelegramController@setWebHook');
Route::get("register", function (){
    abort(404);
});
Route::get('/', function () {
    return view('welcome');
});
Route::post('api/messages/file','App\Http\Controllers\Api\MessageController@fileUpload');

Route::prefix('/bill')->name('bill.')->middleware('auth')->group(function () {
    Route::get('/consult', 'App\Http\Controllers\BillController@consult')->name('consult');
    Route::get('/back/{bill}', 'App\Http\Controllers\BillController@back')->name('back');
    Route::get('/', 'App\Http\Controllers\BillController@index')->name('index');
    Route::get('/accept', 'App\Http\Controllers\BillController@accept')->name('accept');
    Route::get('/accepted', 'App\Http\Controllers\BillController@accepted')->name('accepted');
    Route::get('/declined', 'App\Http\Controllers\BillController@declined')->name('declined');
    Route::get('/my', 'App\Http\Controllers\BillController@my')->name('my');
    Route::get('/form/{bill?}', 'App\Http\Controllers\BillController@form')->name('form');
    Route::get('/delete/{bill}', 'App\Http\Controllers\BillController@delete')->name('delete');
    Route::post('/store', 'App\Http\Controllers\BillController@store')->name('store');
    Route::get('/{bill}', 'App\Http\Controllers\BillController@view')->name('view');
    Route::get('print/{bill}', 'App\Http\Controllers\BillController@printBill')->name('print');
});
Route::prefix('/applications')->name('application.')->middleware('auth')->group(function () {
    Route::get('/worked/{bill}', 'App\Http\Controllers\ApplicationController@worked')->name('worked');
    Route::get('/', 'App\Http\Controllers\ApplicationController@index')->name('index');
    Route::get('/accept', 'App\Http\Controllers\ApplicationController@accept')->name('accept');
    Route::get('/accepted', 'App\Http\Controllers\ApplicationController@accepted')->name('accepted');
    Route::get('/my', 'App\Http\Controllers\ApplicationController@my')->name('my');
});
Route::prefix('/nets')->name('net.')->middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\NetController@index')->name('index');
    Route::get('/form/{net?}', 'App\Http\Controllers\NetController@form')->name('form');
    Route::get('/delete/{net?}', 'App\Http\Controllers\NetController@delete')->name('delete');
    Route::post('/', 'App\Http\Controllers\NetController@store')->name('store');
});
Route::prefix('/products')->name('product.')->middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\ProductController@index')->name('index');
    Route::get('/form/{product?}', 'App\Http\Controllers\ProductController@form')->name('form');
    Route::get('/delete/{product?}', 'App\Http\Controllers\ProductController@delete')->name('delete');
    Route::post('/', 'App\Http\Controllers\ProductController@store')->name('store');
});
Route::prefix('/merchandisings')->name('merchandising.')->middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\MerchandisingController@index')->name('index');
    Route::get('/form/{merchandising?}', 'App\Http\Controllers\MerchandisingController@form')->name('form');
    Route::get('/delete/{merchandising?}', 'App\Http\Controllers\MerchandisingController@delete')->name('delete');
    Route::post('/', 'App\Http\Controllers\MerchandisingController@store')->name('store');
    Route::get('/excel', 'App\Http\Controllers\MerchandisingController@createExcel')->name('excel');
});
Route::prefix('/contracts')->name('contracts.')->middleware('auth')->group(function () {
    Route::get('/form/{contract?}/', 'App\Http\Controllers\ContractController@form')->name('form');
    Route::get('/{contract}', 'App\Http\Controllers\ContractController@show')->name('show');
    Route::post('/{contract?}', 'App\Http\Controllers\ContractController@store')->name('store');
    Route::get('/', 'App\Http\Controllers\ContractController@index')->name('index');
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
    Route::get('/create', 'App\Http\Controllers\UserController@create')->name('create');
});
Route::prefix('/kanban')->name('kanban.')->middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\KanbanController@index')->name('index');
    Route::get('/form/{kanban_task?}', 'App\Http\Controllers\KanbanController@form')->name('form');
    Route::post('/store/{kanban_task?}', 'App\Http\Controllers\KanbanController@store')->name('store');
    Route::get('/destroy/{kanban_task?}', 'App\Http\Controllers\KanbanController@destroy')->name('destroy');
});
Route::prefix('/clients')->name('clients.')->middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\ClientController@index')->name('index');
    Route::get('/form/{client?}', 'App\Http\Controllers\ClientController@form')->name('form');
    Route::post('/store/{client?}', 'App\Http\Controllers\ClientController@store')->name('store');
    Route::get('/{client}', 'App\Http\Controllers\ClientController@view')->name('view');
});
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return redirect()->route('bill.index');
})->name('dashboard');
