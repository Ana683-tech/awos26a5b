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

    protected $fillable = [
        'id_pedido',
        'id_cliente',
        'fecha_pedido',
        'total',
        'metodo_pago',     // Asegúrate de que coincida con tu controlador
        'direccion_envio', // Asegúrate de que coincida con tu controlador
        'estado_pago'      // (0: pendiente, 1: pagado o string)
    ];

    // Relación: Un pedido pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    // --- ESTA ES LA FUNCIÓN QUE TE FALTA ---
    public function detalles()
    {
        // Un pedido tiene muchos detalles
        // 'id_pedido' es la llave foránea en la tabla detalle_pedidos
        return $this->hasMany(DetallePedido::class, 'id_pedido', 'id_pedido');
    }
}