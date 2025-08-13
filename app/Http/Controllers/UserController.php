<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserController extends Controller
{
    public function Register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nombre' => 'required|max:255',
            'apellido' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'CI' => 'required|max:20',
            'Telefono' => 'required|max:15',
            'Direccion' => 'required|max:255',
            'Estado_Registro' => 'required|in:Pendiente, Aceptado, Rechazado',
            'Tipo_Persona' => 'required',
        ]);

        if ($validation->fails()) {
            return response($validation->errors(), 401);
        }

        return $this->createUser($request);
    }

    private function createUser($request)
    {
        $user = new User();
        $user->nombre = $request->post("nombre");
        $user->apellido = $request->post("apellido");
        $user->email = $request->post("email");
        $user->password = Hash::make($request->post("password"));
        $user->CI = $request->post("CI");
        $user->Telefono = $request->post("Telefono");
        $user->Direccion = $request->post("Direccion");
        $user->Estado_Registro = $request->post("Estado_Registro");
        $user->Tipo_Persona = $request->post("Tipo_Persona");
        $user->save();
        return response()->json($user, 201);
    }

    public function ValidateToken(Request $request)
    {
        return response()->json(auth('api')->user());
    }

    public function Logout(Request $request)
    {
        $request->user()->token()->revoke();
        return ['message' => 'Token Revoked'];
    }

    public function login(Request $request)
    {
        // Validar datos de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Buscar usuario por email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 401);
        }

        // Verificar estado del registro
        if ($user->Estado_Registro !== 'Aceptado') {
            return response()->json(['error' => 'Usuario no autorizado. Estado: ' . $user->Estado_Registro], 403);
        }

        // Preparar datos para oauth/token
        $postData = [
            'grant_type' => 'password',
            'client_id' => config('services.passport.password_client_id'),
            'client_secret' => config('services.passport.password_client_secret'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ];

        // Crear una Request interna hacia /oauth/token y manejarla dentro de la app
        $internalRequest = \Illuminate\Http\Request::create('/oauth/token', 'POST', $postData);
        $response = app()->handle($internalRequest);
        $status = $response->getStatusCode();
        $content = $response->getContent();

        $decoded = json_decode($content, true);

        if ($status >= 400) {
            return response()->json(
                $decoded ?? ['message' => $content],
                $status
            );
        }

        // Responder con el contenido exitoso (access_token, expires_in, refresh_token si aplica)
        return response()->json($decoded ?? json_decode($content), $status);
    }

    public function update(Request $request)
    {
        $authUser = auth('api')->user();
        $user = User::find($authUser->id);

        $validation = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|max:255',
            'apellido' => 'sometimes|required|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'CI' => 'sometimes|required|max:20',
            'Telefono' => 'sometimes|required|max:15',
            'Direccion' => 'sometimes|required|max:255',
            'Tipo_Persona' => 'sometimes|required',
            // No permitas actualizar Estado_Registro aquí
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        $user->fill($request->only([
            'nombre',
            'apellido',
            'email',
            'CI',
            'Telefono',
            'Direccion',
            'Tipo_Persona'
        ]));

        $user->save();

        return response()->json([
            'message' => 'Datos actualizados correctamente',
            'user' => $user
        ]);
    }
}
