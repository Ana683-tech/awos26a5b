<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';
    public $timestamps = false;

    // --- CORRECCIÓN AQUÍ ---
    protected $fillable = [
        'id_pedido',
        'id_cliente',
        'fecha_pedido',
        'total',
        'direccion',      // Añadido para la Act 23
        'estado_pago'     // Añadido para la Act 23 (0: pendiente, 1: pagado)
    ];

    // Relación: Un pedido pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}