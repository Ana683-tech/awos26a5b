<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// 1. IMPORTA EL TRAIT DE SANCTUM
use Laravel\Sanctum\HasApiTokens; 

class Cliente extends Model
{
    // 2. USA EL TRAIT AQUÍ
    use HasApiTokens; 

    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';
    public $timestamps = false;
    
    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono',
        'email',
        'usuario',
        'contrasena',
        'imagen',
        'estado'
    ];
}