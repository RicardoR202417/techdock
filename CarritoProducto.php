<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarritoProducto extends Model
{
    protected $table = 'carrito_productos';

    protected $fillable = ['id_carrito', 'id_producto', 'cantidad'];

    public $timestamps = false; // Deshabilitar las marcas de tiempo

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}