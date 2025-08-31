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
            'email' => 'required|email|unique:users',
            'CI' => 'required|max:20',
            'telefono' => 'required|max:20',
            'direccion' => 'required|max:255',
            'unidadHabitacional' => 'nullable|max:255',
            'estadoCivil' => 'nullable|max:255',
            'genero' => 'nullable|max:50',
            'fechaNacimiento' => 'nullable|date',
            'ocupacion' => 'nullable|max:255',
            'nacionalidad' => 'nullable|max:255',
            'estadoRegistro' => 'required|max:50',
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
            'password' => null
        ]);

        // Crear persona asociada con los nuevos campos
        $persona = $user->persona()->create([
            'name' => $request->post('name'),
            'apellido' => $request->post('apellido'),
            'CI' => $request->post('CI'),
            'telefono' => $request->post('telefono'),
            'direccion' => $request->post('direccion'),
            'unidadHabitacional' => $request->post('unidadHabitacional'),
            'estadoCivil' => $request->post('estadoCivil'),
            'genero' => $request->post('genero'),
            'fechaNacimiento' => $request->post('fechaNacimiento'),
            'ocupacion' => $request->post('ocupacion'),
            'nacionalidad' => $request->post('nacionalidad'),
            'estadoRegistro' => $request->post('estadoRegistro'),
            'Activo' => 'No'
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

    public function ChangePassword(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validation = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        if (!Hash::check($request->post('old_password'), $user->password)) {
            return response()->json(['error' => 'La contraseña actual es incorrecta'], 400);
        }

        $user->password = Hash::make($request->post('new_password'));
        $user->save();

        // Cambiar 'Activo' a 'Si' en Persona
        $persona = $user->persona;
        if ($persona) {
            $persona->Activo = 'Si';
            $persona->save();
        }

        return response()->json(['message' => 'Contraseña cambiada exitosamente', 'user' => $user, 'persona' => $persona]);
    }
}
