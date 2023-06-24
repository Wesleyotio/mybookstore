<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostsController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [UserController::class, 'register']);
Route::post('/login',  [UserController::class, 'login']);
Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/user/update', [UserController::class, 'update']);
});
Route::middleware('auth:sanctum')->get('/books', 'Controller@index');
Route::middleware('auth:sanctum')->post('/books', 'BookController@create');
Route::middleware('auth:sanctum')->put('/books/{id}', 'BookController@update');
Route::middleware('auth:sanctum')->delete('/books/{id}', 'BookController@delete');
