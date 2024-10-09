<?php

use App\Models\User;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\Api\userController;
use App\Http\Controllers\API\TopicController;
use App\Http\Controllers\Api\BudayaController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\Api\KoordinatController;

Route::post('/register', [userController::class, 'register']);
Route::post('/login', [userController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', function () {
        return UserResource::collection(User::all());
    });

    Route::apiResource('budaya', BudayaController::class)->except(['show', 'destroy']);
    Route::apiResource('koordinat', KoordinatController::class)->except(['show', 'destroy']);

    Route::apiResources([
        'categories' => CategoryController::class,
        'topics' => TopicController::class,
        'posts' => PostController::class,
        'comments' => CommentController::class
    ]);

    Route::post('/logout', [userController::class, 'logout']);
});
