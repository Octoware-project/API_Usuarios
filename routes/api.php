<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::post('/user',[UserController::class,"Register"]);
Route::get('/validate',[UserController::class,"ValidateToken"])->middleware('auth:api');
Route::get('/logout',[UserController::class,"Logout"])->middleware('auth:api');
Route::post('/user/change-password', [App\Http\Controllers\UserController::class, 'ChangePassword'])->middleware('auth:api');


// Ruta para login usando client_id y client_secret desde .env
Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);

// Nueva ruta para completar datos de persona
Route::post('/completar-datos', [UserController::class, 'completarDatos'])->middleware('auth:api');

// Ruta para editar datos de persona autenticada
Route::post('/editar-datos-persona', [UserController::class, 'editarDatosPersona'])->middleware('auth:api');
