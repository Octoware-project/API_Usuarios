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
            'Telefono' => 'required|max:20',
            'Direccion' => 'required|max:255',
            'Estado_Registro' => 'required|max:50',
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

        // Crear persona asociada
        $persona = $user->persona()->create([
            'name' => $request->post('name'),
            'apellido' => $request->post('apellido'),
            'CI' => $request->post('CI'),
            'Telefono' => $request->post('Telefono'),
            'Direccion' => $request->post('Direccion'),
            'Estado_Registro' => $request->post('Estado_Registro')
        ]);

        return response()->json([
            'user' => $user,
            'persona' => $persona
        ]);
    }

    public function ValidateToken(Request $request)
    {
        return auth('api')->user();
    }

    public function Logout(Request $request)
    {
        $request->user()->token()->revoke();
        return ['message' => 'Token Revoked'];
    }
}
