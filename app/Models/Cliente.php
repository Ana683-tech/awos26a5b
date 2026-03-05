<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens; // <--- DEBE SER SANCTUM
use Illuminate\Notifications\Notifiable;

class Cliente extends Authenticatable 
{
    use HasApiTokens, Notifiable;

    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';
    public $timestamps = false;
    
    protected $fillable = [
        'nombre', 'apellidos', 'telefono', 'email', 
        'usuario', 'contrasena', 'imagen', 'estado'
    ];

    protected $hidden = ['contrasena'];

    public function getAuthPassword() {
        return $this->contrasena;
    }
}