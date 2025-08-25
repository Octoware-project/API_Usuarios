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
    public function Register(Request $request){

        $validation = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        if($validation->fails())
            return response($validation->errors(), 401);

        return $this -> createUser($request);
        
    }

 private function createUser($request){
    // Crear User
    $user = new User();
    $user->name = $request->post("name");
    $user->email = $request->post("email");
    $user->password = Hash::make($request->post("password"));   
    $user->save();

    // Crear Persona vinculada (con null en lo demás)
    $persona = new Persona();
    $persona->user_id = $user->id;
    $persona->nombre = $request->post("name");  // opcional, podés dejarlo null también
    $persona->save();

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
