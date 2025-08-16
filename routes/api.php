<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

// Registrar user -> /api/user (POST)
Route::post('user', [UserController::class, 'store']);

// Token -> /oauth/token (POST) <- manejado por Passport

// Validar token -> /api/validate (GET) protegido por Passport
Route::middleware('auth:api')->group(function () {
    Route::get('validate', [UserController::class, 'validateToken']);
});