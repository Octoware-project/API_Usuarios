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
            ['Juan', 'Perez', 'juan@example.com', '123456', '1234567', '555-1234', 'Calle Falsa 123'],
            ['Maria', 'Gomez', 'maria@example.com', '123456', '7654321', '555-5678', 'Avenida Siempre Viva 742'],
            ['Carlos', 'Lopez', 'carlos@example.com', '123456', '2345678', '555-2345', 'Calle Luna 10'],
            ['Lucia', 'Martinez', 'lucia@example.com', '123456', '8765432', '555-8765', 'Calle Sol 99'],
            ['Pedro', 'Ramirez', 'pedro@example.com', '123456', '3456789', '555-3456', 'Avenida Central 50'],
            ['Ana', 'Fernandez', 'ana@example.com', '123456', '9876543', '555-9876', 'Calle Norte 77'],
            ['Luis', 'Gonzalez', 'luis@example.com', '123456', '4567890', '555-4567', 'Calle Sur 21'],
            ['Sofia', 'Diaz', 'sofia@example.com', '123456', '8765432', '555-8765', 'Avenida Oeste 5'],
            ['Miguel', 'Vargas', 'miguel@example.com', '123456', '5678901', '555-5678', 'Calle Este 12'],
            ['Carla', 'Torres', 'carla@example.com', '123456', '7654321', '555-7654', 'Avenida Principal 88'],
        ];

        foreach ($usuarios as $u) {
            // Insertar usuario y obtener ID
            $user_id = DB::table('users')->insertGetId([
                'name' => $u[0] . ' ' . $u[1],
                'email' => $u[2],
                'password' => Hash::make($u[3]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insertar persona relacionada
            DB::table('personas')->insert([
                'user_id' => $user_id,
                'nombre' => $u[0],
                'apellido' => $u[1],
                'CI' => $u[4],
                'Telefono' => $u[5],
                'Direccion' => $u[6],
                'Estado_Registro' => 'Pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
