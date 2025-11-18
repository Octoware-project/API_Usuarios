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

        
        return $this->createUser($request);
    }
    private function createUser($request)
    {
        
        $user = User::create([
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'password' => Hash::make($request->post('password')),
        ]);

       
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
       
        $user = auth('api')->user()->load('persona');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
       
        $response = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            ],
            'persona' => $user->persona ? [
                'id' => $user->persona->id,
                'name' => $user->persona->name,
                'apellido' => $user->persona->apellido,
                'CI' => $user->persona->CI,
                'telefono' => $user->persona->telefono,
                'direccion' => $user->persona->direccion,
                'estadoCivil' => $user->persona->estadoCivil,
                'genero' => $user->persona->genero,
                'fechaNacimiento' => $user->persona->fechaNacimiento,
                'ocupacion' => $user->persona->ocupacion,
                'nacionalidad' => $user->persona->nacionalidad,
                'estadoRegistro' => $user->persona->estadoRegistro,
            ] : null
        ];
        
        return response()->json($response)->header('Cache-Control', 'no-cache, must-revalidate');
    }

    public function Logout(Request $request)
    {
        $request->user()->token()->revoke();
        return ['message' => 'Token Revoked'];
    }


}
