<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventario'; // Nombre de la tabla

    protected $primaryKey = 'id_inv'; // Clave primaria

    protected $fillable = [
        'id_prod',
        'cant_disp',
    ];

    public $timestamps = false; // Deshabilitar created_at y updated_at

    // Relación con el modelo Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_prod');
    }
}