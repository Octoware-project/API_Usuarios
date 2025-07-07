<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/',[UsuarioController::class, "Index"] );
Route::post('/usuarios',[UsuarioController::class, "Registrar"] );