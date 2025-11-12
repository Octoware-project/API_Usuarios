<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Persona;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;


class UserFeatureTest extends TestCase
{
    use DatabaseTransactions;

    public function test_PuedeRegistrarUsuario()
    {
        $uniqueEmail = 'test_' . time() . '@example.com';
        $uniqueCI = '9' . time(); 

        $data = [
            'name' => 'Juan',
            'apellido' => 'Pérez',
            'CI' => $uniqueCI,
            'email' => $uniqueEmail,
            'password' => 'miclave123',
            'estadoRegistro' => 'Pendiente',
        ];

        $response = $this->postJson('/api/user', $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => $uniqueEmail]);
        $this->assertDatabaseHas('personas', ['CI' => $uniqueCI]);
    }

    public function test_UsuarioPuedeValidarToken()
    {
        $user = User::first();
        
        if (!$user) {
            $this->markTestSkipped('No hay usuarios en la base de datos. Ejecuta los seeders primero.');
        }

        Passport::actingAs($user);

        $response = $this->getJson('/api/validate');

        $response->assertStatus(200);
        $response->assertJsonFragment(['email' => $user->email]);
    }

    public function test_UsuarioPuedeHacerLogout()
    {
        $user = User::first();
        
        if (!$user) {
            $this->markTestSkipped('No hay usuarios en la base de datos. Ejecuta los seeders primero.');
        }

        Passport::actingAs($user);

        $response = $this->getJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Token Revoked']);
    }
}
