<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserPersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'name' => 'Juan',
                'apellido' => 'Perez',
                'email' => 'juan@example.com',
                'password' => null,
                'CI' => '1234567',
                'Telefono' => '555-1234',
                'Direccion' => 'Calle Falsa 123',
                'UnidadHabitacional' => 'A1',
                'EstadoCivil' => 'Soltero',
                'Genero' => 'Masculino',
                'FechaNacimiento' => '1990-01-01',
                'Ocupacion' => 'Ingeniero',
                'Nacionalidad' => 'Argentina',
                'estadoRegistro' => 'Pendiente',
                'activo' => 'No'
            ],
            [
                'name' => 'Maria',
                'apellido' => 'Gomez',
                'email' => 'maria@example.com',
                'password' => '123456',
                'CI' => '7654321',
                'Telefono' => '555-5678',
                'Direccion' => 'Avenida Siempre Viva 742',
                'UnidadHabitacional' => 'B2',
                'EstadoCivil' => 'Casada',
                'Genero' => 'Femenino',
                'FechaNacimiento' => '1985-05-10',
                'Ocupacion' => 'Doctora',
                'Nacionalidad' => 'Uruguaya',
                'estadoRegistro' => 'Pendiente',
                'activo' => 'Si'
            ],
            // ...agrega más usuarios si lo deseas...
        ];

        foreach ($usuarios as $u) {
            // Insertar usuario y obtener ID
            $user_id = DB::table('users')->insertGetId([
                'name' => $u['name'] . ' ' . $u['apellido'],
                'email' => $u['email'],
                'password' => Hash::make($u['password']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insertar persona relacionada
            DB::table('personas')->insert([
                'user_id' => $user_id,
                'name' => $u['name'],
                'apellido' => $u['apellido'],
                'CI' => $u['CI'],
                'Telefono' => $u['Telefono'],
                'Direccion' => $u['Direccion'],
                'UnidadHabitacional' => $u['UnidadHabitacional'],
                'EstadoCivil' => $u['EstadoCivil'],
                'Genero' => $u['Genero'],
                'FechaNacimiento' => $u['FechaNacimiento'],
                'Ocupacion' => $u['Ocupacion'],
                'Nacionalidad' => $u['Nacionalidad'],
                'estadoRegistro' => $u['estadoRegistro'],
                'activo' => $u['activo'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
