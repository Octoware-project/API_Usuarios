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

}
