<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Parser;
use Illuminate\Support\Facades\Validator;




class UserController extends Controller
{
    public function Register(Request $request){

        $validation = Validator::make($request->all(),[
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'CI' => 'required|string|max:20',
            'telefono' => 'required|string|max:15',
            'direccion' => 'required|string|max:255',
            'estado_Registro' => 'required',
            'tipo_Persona' => 'required|string|max:50',
        ]);

        if($validation->fails())
            return response($validation->errors(), 401);

        return $this -> createUser($request);
        
    }

    private function createUser($request){
        $user = new User();
        $user -> nombre = $request -> post("nombre");
        $user -> apellido = $request -> post("apellido");
        $user -> email = $request -> post("email");
        $user -> password = Hash::make($request -> post("password"));   
        $user -> CI = $request -> post("CI");
        $user -> telefono = $request -> post("telefono");
        $user -> direccion = $request -> post("direccion");
        $user -> estado_Registro = $request -> post("estado_Registro");
        $user -> tipo_Persona = $request -> post("tipo_Persona");
        $user -> save();
        return $user;
    }

    public function ValidateToken(Request $request){
        return auth('api')->user();
    }

    public function Logout(Request $request){
        $request->user()->token()->revoke();
        return ['message' => 'Token Revoked'];
        
        
    }

    
}
