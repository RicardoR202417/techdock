<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalle_venta'; // Nombre de la tabla en la base de datos

    public $incrementing = false; // Deshabilitar auto-incremento, ya que no hay un campo ID único

    protected $primaryKey = ['id_inv', 'id_venta']; // Clave primaria compuesta

    public $timestamps = false; // Deshabilitar timestamps automáticos

    protected $fillable = [
        'id_inv',     // ID del inventario relacionado
        'id_venta',   // ID de la venta relacionada
        'cant_comp',  // Cantidad comprada
    ];

    // Relación con el modelo Venta
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta');
    }

    // Relación con el modelo Inventario
    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'id_inv', 'id_inv');
    }
}