<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carritos';

    protected $fillable = ['id_usuario'];

    public function productos()
    {
        return $this->hasMany(CarritoProducto::class, 'id_carrito');
    }
}