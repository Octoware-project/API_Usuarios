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
            'user_id' => User::factory(),
            'name' => $this->faker->firstName,
            'apellido' => $this->faker->lastName,
            'CI' => $this->faker->numerify('########'),
            'Telefono' => $this->faker->phoneNumber,
            'Direccion' => $this->faker->address,
            'Estado_Registro' => 'Aceptado',
        ];
    }
}
