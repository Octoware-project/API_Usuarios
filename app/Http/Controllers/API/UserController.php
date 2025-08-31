<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Persona;

class UserController extends Controller
{
    /**
     * POST /api/user
     * Crea user (solo name,email,password) y persona con todos los datos.
     * Responde: { success: true/false, user: { id, name, email, created_at } }
     */
    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|max:255',
            'apellido' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'CI' => 'required|max:20',
            'Telefono' => 'required|max:15',
            'Direccion' => 'required|max:255',
            'Estado_Registro' => 'required|in:Pendiente,Aceptado,Rechazado',
            'Tipo_Persona' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Usamos transacción para asegurar coherencia
        $user = null;
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => trim($request->nombre . ' ' . $request->apellido),
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->persona()->create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'CI' => $request->CI,
                'Telefono' => $request->Telefono,
                'Direccion' => $request->Direccion,
                'Estado_Registro' => $request->Estado_Registro,
                'Tipo_Persona' => $request->Tipo_Persona,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Opcional: loguear $e->getMessage()
            return response()->json([
                'success' => false,
                'message' => 'Error creando usuario',
            ], 500);
        }

        $userData = $user->only(['id', 'name', 'email', 'created_at', 'updated_at']);

        return response()->json([
            'success' => true,
            'user' => $userData,
        ], 201);
    }

    /**
     * GET /api/validate
     * Ruta protegida: valida token via Passport y devuelve solo la persona asociada.
     * Responde: { success: true/false, persona: { ... } }
     */
    public function validateToken(Request $request)
    {
        $user = $request->user(); // Passport inyecta el user si token válido

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'No autenticado'], 401);
        }

        $persona = $user->persona;

        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'No existe información de persona para este usuario'
            ], 404);
        }

        // Devolver solo los campos de persona (no incluir user_id)
        $personaData = $persona->only([
            'persona_id' => 'id', // we'll map below since only() with rename not possible
            'id','nombre','apellido','email','CI','Telefono','Direccion','Estado_Registro','Tipo_Persona','created_at','updated_at'
        ]);

        // Mapear persona_id de forma explícita
        $personaData = $persona->toArray();
        $personaData['persona_id'] = $personaData['id'];
        unset($personaData['id'], $personaData['user_id']);

        return response()->json([
            'success' => true,
            'persona' => $personaData,
        ]);
    }
}
