<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function Index(){
        
        $usuarios = Usuario::all();

        if ($usuarios->isEmpty()) {
            return response()->json(['message' => 'No hay usuarios registrados'], 404);
        }

        return response()->json($usuarios, 200); 
    }

    public function Registrar(Request $request){
        $usuarios = Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido
        ]);

        if (!$usuarios) {
            return response()->json(['message' => 'Error al registrar el usuario'], 500);
        }

        return response()->json(['message' => 'Usuario registrado correctamente', 'usuario' => $usuarios], 201); 
    }   
}
