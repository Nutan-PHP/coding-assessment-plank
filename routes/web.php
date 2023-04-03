<?php

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/quizzes', [\App\Http\Controllers\QuizController::class, 'index']);
Route::get('/quiz/{id}', [\App\Http\Controllers\QuizController::class, 'show']);
Route::post('/quiz', [\App\Http\Controllers\QuizController::class, 'store']);
Route::post('/attempt', [\App\Http\Controllers\QuizAttemptController::class, 'store']);
Route::delete('/quiz/{id}', [\App\Http\Controllers\QuizController::class, 'destroy']);
