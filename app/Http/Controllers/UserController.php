<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Parser;
use Illuminate\Support\Facades\Validator;




class UserController extends Controller   
{
    public function Register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'apellido' => 'required|max:255',
            'CI' => 'required|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validation->fails())
            return response($validation->errors(), 401);

        // 👉 PASAR EL REQUEST
        return $this->createUser($request);
    }
    private function createUser($request)
    {
        // Crear usuario
        $user = User::create([
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'password' => Hash::make($request->post('password')),
        ]);

        // Crear persona asociada con los nuevos campos
        $persona = $user->persona()->create([
            'name' => $request->post('name'),
            'apellido' => $request->post('apellido'),
            'CI' => $request->post('CI'),
            'estadoRegistro' => $request->post('estadoRegistro'),
        ]);

        return response()->json([
            'user' => $user,
            'persona' => $persona
        ]);
    }

    public function ValidateToken(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $persona = $user->persona;
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                // Agrega aquí otros campos si lo necesitas
            ],
            'persona' => $persona
        ]);
    }

    public function Logout(Request $request)
    {
        $request->user()->token()->revoke();
        return ['message' => 'Token Revoked'];
    }

    // Completar datos de la persona autenticada
    public function completarDatos(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }
        $persona = $user->persona;
        if (!$persona) {
            return response()->json(['message' => 'No se encontró la persona asociada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'telefono' => 'required|string|max:30',
            'direccion' => 'required|string|max:255',
            'estadoCivil' => 'required|string|max:50',
            'genero' => 'required|string|max:50',
            'fechaNacimiento' => 'required|date',
            'ocupacion' => 'required|string|max:100',
            'nacionalidad' => 'required|string|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

    // Actualizar datos (solo los permitidos)
    $persona->telefono = $request->input('telefono');
    $persona->direccion = $request->input('direccion');
    $persona->estadoCivil = $request->input('estadoCivil');
    $persona->genero = $request->input('genero');
    $persona->fechaNacimiento = $request->input('fechaNacimiento');
    $persona->ocupacion = $request->input('ocupacion');
    $persona->nacionalidad = $request->input('nacionalidad');
    $persona->estadoRegistro = 'Aceptado';
    $persona->save();

        return response()->json([
            'message' => 'Datos completados correctamente',
            'persona' => $persona
        ]);
    }

        // Editar datos de persona autenticada (solo campos editables)
    public function editarDatosPersona(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }
        $persona = $user->persona;
        if (!$persona) {
            return response()->json(['message' => 'No se encontró la persona asociada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'telefono' => 'required|string|max:30',
            'direccion' => 'required|string|max:255',
            'estadoCivil' => 'required|string|max:50',
            'genero' => 'required|string|max:50',
            'fechaNacimiento' => 'required|date',
            'ocupacion' => 'required|string|max:100',
            'nacionalidad' => 'required|string|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $persona->telefono = $request->input('telefono');
        $persona->direccion = $request->input('direccion');
        $persona->estadoCivil = $request->input('estadoCivil');
        $persona->genero = $request->input('genero');
        $persona->fechaNacimiento = $request->input('fechaNacimiento');
        $persona->ocupacion = $request->input('ocupacion');
        $persona->nacionalidad = $request->input('nacionalidad');
        $persona->save();

        return response()->json([
            'message' => 'Datos personales actualizados correctamente',
            'persona' => $persona
        ]);
    }
}
