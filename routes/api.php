<?php

use App\Http\Controllers\AboutUsApiController;
use App\Http\Controllers\DestinationApiController;
use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\TourCategoryApiController;
use App\Http\Controllers\ToursApiController;
use App\Http\Controllers\UsersApiController;
use Illuminate\Support\Facades\Route;



//public Routes
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);

Route::get('/destinations', [DestinationApiController::class, 'index']);
Route::get('/destinations/{slug}', [DestinationApiController::class, 'show']);

Route::get('/ToursCategories', [TourCategoryApiController::class, 'index']);
Route::get('/ToursCategories/{slug}', [TourCategoryApiController::class, 'show']);

Route::get('/Tours', [ToursApiController::class, 'index']);
Route::get('/Tours/{slug}', [ToursApiController::class, 'show']);

Route::get('/AboutUs', [AboutUsApiController::class, 'index']);


//protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/users', [UsersApiController::class, 'index']);
    Route::get('/users/{id}', [UsersApiController::class, 'show']);
    Route::Patch('/users/{id}', [UsersApiController::class, 'update']);
    Route::Delete('/users/{id}', [UsersApiController::class, 'destroy']);
    Route::post('/logout', [AuthApiController::class, 'logout']);
});
