<?php

namespace Database\Factories;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonaFactory extends Factory
{
    protected $model = Persona::class;

    public function definition()
    {
        return [
            // 'user_id' se asigna manualmente en el seeder para evitar usuarios huérfanos
            'name' => $this->faker->firstName,
            'apellido' => $this->faker->lastName,
            'CI' => $this->faker->numerify('########'),
            'telefono' => $this->faker->phoneNumber,
            'direccion' => $this->faker->address,
            'estadoCivil' => $this->faker->randomElement(['Soltero', 'Casado', 'Divorciado', 'Viudo']),
            'genero' => $this->faker->randomElement(['Masculino', 'Femenino', 'Otro']),
            'fechaNacimiento' => $this->faker->date(),
            'ocupacion' => $this->faker->jobTitle,
            'nacionalidad' => $this->faker->country,
            'estadoRegistro' => 'Aceptado',
        ];
    }

    public function pendiente()
    {
        return $this->state([
            'estadoRegistro' => 'Pendiente',
        ]);
    }

    public function aceptado()
    {
        return $this->state([
            'estadoRegistro' => 'Aceptado',
        ]);
    }
}
