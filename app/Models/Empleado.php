<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Cambiado para Auth
use Laravel\Sanctum\HasApiTokens; // Importado

class Empleado extends Authenticatable // Cambiado de Model a Authenticatable
{
    use HasApiTokens, HasFactory; // Agregado HasApiTokens

    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado'; 
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'puesto',
        'usuario',
        'contrasena',
        'salario',
        'estado',
        'email',
        'imagen'
    ];

    // Importante para que Sanctum reconozca la contraseña con otro nombre
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}