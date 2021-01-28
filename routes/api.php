<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('messages','App\Http\Controllers\Api\MessageController');
Route::resource('user_roles','App\Http\Controllers\Api\UserRoleController');
Route::resource('chains','App\Http\Controllers\Api\ChainController');
Route::resource('organisations','App\Http\Controllers\Api\OrganisationController');
Route::resource('users','App\Http\Controllers\Api\UserController');
Route::resource('clients','App\Http\Controllers\Api\ClientController');
Route::resource('kanban_columns','App\Http\Controllers\Api\KanbanColumnController');
Route::resource('kanban_tasks','App\Http\Controllers\Api\KanbanTaskController');

