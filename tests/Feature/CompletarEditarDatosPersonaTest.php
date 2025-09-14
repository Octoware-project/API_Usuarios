<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Persona;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class CompletarEditarDatosPersonaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_puede_completar_datos_persona()
    {
        Artisan::call('passport:install');
        $user = User::factory()->create();
        $persona = $user->persona()->create([
            'name' => 'Juan',
            'apellido' => 'Pérez',
            'CI' => '12345678',
            'estadoRegistro' => 'Pendiente',
        ]);
        Passport::actingAs($user);
        $data = [
            'telefono' => '123456789',
            'direccion' => 'Calle Falsa 123',
            'estadoCivil' => 'Soltero',
            'genero' => 'Masculino',
            'fechaNacimiento' => '1990-01-01',
            'ocupacion' => 'Ingeniero',
            'nacionalidad' => 'Argentina',
        ];
        $response = $this->postJson('/api/completar-datos', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('personas', [
            'user_id' => $user->id,
            'telefono' => '123456789',
            'estadoRegistro' => 'Aceptado',
        ]);
    }

    /** @test */
    public function usuario_puede_editar_datos_persona()
    {
        Artisan::call('passport:install');
        $user = User::factory()->create();
        $persona = $user->persona()->create([
            'name' => 'Juan',
            'apellido' => 'Pérez',
            'CI' => '12345678',
            'telefono' => '111',
            'direccion' => 'Vieja',
            'estadoCivil' => 'Soltero',
            'genero' => 'Masculino',
            'fechaNacimiento' => '1990-01-01',
            'ocupacion' => 'Estudiante',
            'nacionalidad' => 'Argentina',
            'estadoRegistro' => 'Aceptado',
        ]);
        Passport::actingAs($user);
        $data = [
            'telefono' => '999999',
            'direccion' => 'Nueva Direccion',
            'estadoCivil' => 'Casado',
            'genero' => 'Masculino',
            'fechaNacimiento' => '1990-01-01',
            'ocupacion' => 'Doctor',
            'nacionalidad' => 'Uruguayo',
        ];
        $response = $this->postJson('/api/editar-datos-persona', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('personas', [
            'user_id' => $user->id,
            'telefono' => '999999',
            'direccion' => 'Nueva Direccion',
            'estadoCivil' => 'Casado',
            'ocupacion' => 'Doctor',
            'nacionalidad' => 'Uruguayo',
        ]);
    }
}
