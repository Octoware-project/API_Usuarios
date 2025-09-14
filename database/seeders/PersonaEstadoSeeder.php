<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;

class PersonaEstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Primer usuario específico
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);
        $persona = \App\Models\Persona::factory()->aceptado()->make([
            'name' => 'test',
            'apellido' => 'User',
        ]);
        $persona->user_id = $user->id;
        $persona->save();

        // 4 usuarios random aceptados
        for ($i = 0; $i < 4; $i++) {
            $user = \App\Models\User::factory()->create();
            $persona = \App\Models\Persona::factory()->aceptado()->make();
            $persona->user_id = $user->id;
            $persona->save();
        }

        // 5 usuarios random pendientes
        for ($i = 0; $i < 5; $i++) {
            $user = \App\Models\User::factory()->create();
            $persona = \App\Models\Persona::factory()->pendiente()->make();
            $persona->user_id = $user->id;
            $persona->save();
        }
    }
}
