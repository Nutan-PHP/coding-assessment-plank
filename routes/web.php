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
Route::get('/articles/{id?}', [\App\Http\Controllers\ArticlesListing::class, 'get_the_articles']);
Route::get('/author/{id}/articles', [\App\Http\Controllers\ArticlesListing::class, 'get_author_articles']);
Route::post('/article', [\App\Http\Controllers\ArticlesListing::class, 'make_new_article']);
Route::patch('/article/{id}', [\App\Http\Controllers\ArticlesListing::class, 'article_edits']);
Route::delete('/article/{id}', [\App\Http\Controllers\ArticlesListing::class, 'delete_article']);