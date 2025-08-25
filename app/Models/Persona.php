<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'CI',
        'Telefono',
        'Direccion',
        'Estado_Registro'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
