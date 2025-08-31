<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Persona;

class CheckPersonaEstado
{
    public function handle(Request $request, Closure $next)
    {
        // Solo para /oauth/token y método POST
        if ($request->is('oauth/token') && $request->isMethod('post')) {
            $username = $request->input('username');
            if ($username) {
                $user = User::where('email', $username)->first();
                if ($user) {
                    $persona = Persona::where('user_id', $user->id)->first();
                    if (!$persona || $persona->estadoRegistro !== 'Aceptado') {
                        return response()->json(['error' => 'Usuario Pendiente'], 403);
                    }
                }
            }
        }
        return $next($request);
    }
}
