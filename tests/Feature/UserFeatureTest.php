<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Persona;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;


class UserFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_registrar_usuario()
    {
        $data = [
            'name' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'juan@example.com',
            'CI' => '12345678',
            'Telefono' => '099111222',
            'Direccion' => 'Calle falsa 123',
            'Estado_Registro' => 'activo',
        ];

        $response = $this->postJson('/api/user', $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'juan@example.com']);
        $this->assertDatabaseHas('personas', ['CI' => '12345678']);
    }

    /** @test */
    public function usuario_puede_validar_token()
    {
        $user = User::factory()->create();

        // Simula que el usuario está autenticado con Passport
        Passport::actingAs($user);

        $response = $this->getJson('/api/validate');

        $response->assertStatus(200);
        $response->assertJsonFragment(['email' => $user->email]);
    }

    /** @test */
    public function usuario_puede_hacer_logout()
    {
        // IMPORTANTE: crear los clients de Passport para la BD de testing
        Artisan::call('passport:install');

        $user = User::factory()->create();

        // ahora createToken() funcionará porque existe el personal access client
        $tokenResult = $user->createToken('test-token');
        $accessToken = $tokenResult->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->getJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Token Revoked']);
    }
}
