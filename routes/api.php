<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserAccessMiddleware;
use Illuminate\Support\Facades\Route;

// Authentifications
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Actions où l'utilisateur doit être authentifié
Route::middleware('auth:sanctum')->group(function () {
    // Routes pour les utilisateurs
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/users/makeAdmin/{key}', [UserController::class, 'makeAdmin']);
    Route::get('/myReservations', [UserController::class, 'getReservations']);
    Route::get('/cancelReservation/{reservation_id}', [UserController::class, 'getReservations']);

    // Routes pour les catégories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);

    // Routes pour les événements
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);
    Route::put('/events/{id}', [EventController::class, 'update']);

    // Routes pour les réservations
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'create']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

    // Middlware pour permettre aux utilisateurs de changer que leurs informations et aux administrateurs de tout changer
    Route::middleware(UserAccessMiddleware::class)->group(function () {
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::get('/users/{id}', [UserController::class, 'show']);
    });

    // Middleware d'administrateur appliqué à ce groupe
    Route::middleware(AdminMiddleware::class)->group(function () {
        // Routes pour les utilisateurs
        Route::get('/users', [UserController::class, 'index']);

        // Routes pour les catégories
        Route::post('/categories', [CategoryController::class, 'create']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // Routes pour les événements
        Route::post('/events', [EventController::class, 'create']);
        Route::delete('/events/{id}', [EventController::class, 'destroy']);
    });
});
