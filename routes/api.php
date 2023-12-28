<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


//public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);
Route::patch('/resetPassword/{resetToken}', [AuthController::class, 'resetPassword']);




//protected routes
Route::group(['middleware' => ['auth:sanctum']], function ()  {

    Route::get('/user', function (Request $request) {
    return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/notes', [NoteController::class, 'index']);

    Route::post('/notes', [NoteController::class, 'store']);

    Route::get('/notes/{id}', [NoteController::class, 'show'])->where('id', '[0-9]+');

    Route::patch('/notes/{id}', [NoteController::class, 'update'])->where('id', '[0-9]+');

    Route::delete('/notes/{id}', [NoteController::class, 'destroy'])->where('id', '[0-9]+');

    Route::get('/notes/print/{id}', [NoteController::class, 'print']);


});
