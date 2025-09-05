<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;



class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ejecutar migraciones
        $this->artisan('migrate');
        // Instalar Passport (crea los clients)
        $this->artisan('passport:install');

        // Crear usuario de prueba
        $user = \App\Models\User::factory()->create([
            'email' => 'usuario@email.com',
            'password' => bcrypt('12345678'),
        ]);
        // Crear persona asociada
        $user->persona()->create([
            'name' => 'Usuario',
            'apellido' => 'Test',
            'CI' => '12345678',
            'estadoRegistro' => 'Aceptado',
        ]);

        // Obtener el primer client creado por passport:install
    $client = DB::table('oauth_clients')->where('password_client', true)->first();
    $this->clientId = $client->id;
    $this->clientSecret = $client->secret;
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */


    // Client creado desde seeders
    private $clientId;
    private $clientSecret;
    private $userName = "usuario@email.com";
    private $userPassword = "12345678";


     public function test_ObtenerTokenConClientIdValido()
    {
            

        $response = $this->post('/oauth/token',[
            "username" => $this -> userName,
            "password" => $this -> userPassword,
            "grant_type" => "password",
            "client_id" => $this -> clientId,
            "client_secret" => $this -> clientSecret
        ]);

        if ($response->status() !== 200) {
            fwrite(STDOUT, "\nRESPONSE: " . $response->getContent() . "\n");
        }

        // Validamos status 200
        $response->assertStatus(200);

        // Validamos que recibamos los campos de Json correspondientes
        $response->assertJsonStructure([
            "token_type",
            "expires_in",
            "access_token",
            "refresh_token"
        ]);

        // Validamos que el campo "token_type" tenga el valor "Bearer"
        $response->assertJsonFragment([
            "token_type" => "Bearer"
        ]);

    }

    public function test_ObtenerTokenConClientIdInvalido()
    {
         
        $response = $this->post('/oauth/token',[
            "grant_type" => "password",
            "client_id" => "234",
            "client_secret" => Str::Random(8)
        ]);

        // Validamos obtener status 401
        $response->assertStatus(401);

        // Validanos JSON obtenido
        $response->assertJsonFragment([
            "error" => "invalid_client",
            "error_description" => "Client authentication failed",
            "message" => "Client authentication failed"
        ]);
    }

    public function test_ValidarTokenSinEnviarToken()
    {
    $response = $this->get('/api/validate');

        // Validamos obtener status 500
        $response->assertStatus(500);
        
    }

    public function test_ValidarTokenConTokenInvalido()
    {
        // Enviamos un string random como Token
        $response = $this->get('/api/validate', [
            "Authorization" => "Bearer " . Str::Random(40)
        ]);

        // Validamos obtener Status 500
        $response->assertStatus(500);
        
    }

    public function test_ValidarTokenConTokenValido()
    {
        // Obtenemos Token
        $tokenResponse = $this->post('/oauth/token',[
            "username" => $this -> userName,
            "password" => $this -> userPassword,
            "grant_type" => "password",
            "client_id" => $this -> clientId,
            "client_secret" => $this -> clientSecret
        ]);

        // Pasamos JSON obtenido a Array
        $token = json_decode($tokenResponse -> content(),true);
        
        // Enviamos peticion para validar token

        $response = $this->get('/api/validate', [
            "Authorization" => "Bearer " . $token['access_token']
        ]);

        // Validamos obtener status 200
        $response->assertStatus(200);
        
    }

    public function test_LogoutSinToken()
    {
        // Enviamos peticion sin Token
    $response = $this->get('/api/logout');

        // Validamos obtener Status 500
        $response->assertStatus(500);
        
    }

    public function test_LogoutConTokenInvalido()
    {
        // Enviamos un string random como Token
        $response = $this->get('/api/logout', [
            "Authorization" => "Bearer " . Str::Random(40)
        ]);

        // Validamos obtener status 500
        $response->assertStatus(500);
        
    }

    public function test_LogoutConTokenValido()
    {
        $tokenResponse = $this->post('/oauth/token',[
            "username" => $this -> userName,
            "password" => $this -> userPassword,
            "grant_type" => "password",
            "client_id" => $this -> clientId,
            "client_secret" => $this -> clientSecret
        ]);

        // Pasamos JSON obtenido a Array
        $token = json_decode($tokenResponse -> content(),true);
        
        // Enviamos peticion para validar token
        $response = $this->get('/api/logout', [
            "Authorization" => "Bearer " . $token['access_token']
        ]);

        // Validamos obtener status 200
        $response->assertStatus(200);

        // Validamos JSON de respuesta
        $response->assertJsonFragment(
            ['message' => 'Token Revoked']
        );
        
    }
}
