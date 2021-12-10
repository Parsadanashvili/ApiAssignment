<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\TagController;
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

Route::get('/articles/{id}/comments', [ArticleController::class, 'articleComments']);
Route::apiResource('articles', ArticleController::class);

Route::get('/tags', [ArticleController::class, 'tags']);
Route::get('/tags/{id}/articles', [ArticleController::class, 'articlesByTags']);

