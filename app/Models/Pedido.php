<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';
    

//____________________________________________________


   //NUEVO MODELO PARA PEDIDOS


//______________________________________________________


    // Si no tienes las columnas created_at y updated_at en tu tabla, pon esto en false
    public $timestamps = false;

    protected $fillable = [
        'id_pedido',
        'id_cliente',
        'fecha_pedido',
        'total'
    ];

    // Relación: Un pedido pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}