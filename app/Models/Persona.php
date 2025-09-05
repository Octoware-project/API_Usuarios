<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'apellido',
        'CI',
        'telefono',
        'direccion',
        'estadoCivil',
        'genero',
        'fechaNacimiento',
        'ocupacion',
        'nacionalidad',
        'estadoRegistro'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
