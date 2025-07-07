<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/',[UsuarioController::class, "Index"] );
Route::post('/',[UsuarioController::class, "Registrar"] );