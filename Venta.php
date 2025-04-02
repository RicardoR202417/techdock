<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'venta'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_venta'; // Clave primaria de la tabla

    public $timestamps = false; // Deshabilitar timestamps automáticos

    protected $fillable = [
        'id_usuario', // ID del usuario que realiza la compra
        'fecha',      // Fecha de la venta
        'iva',        // IVA aplicado a la venta
        'imp_tot',    // Importe total de la venta
    ];

    // Relación con el modelo DetalleVenta
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta');
    }
}