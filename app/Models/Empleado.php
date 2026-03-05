<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Laravel\Sanctum\HasApiTokens; 

class Empleado extends Authenticatable 
{
    use HasApiTokens, HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado'; 
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'apellido', 'puesto', 'usuario',
        'contrasena', 'salario', 'estado', 'email', 'imagen'
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}