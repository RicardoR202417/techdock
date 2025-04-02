<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto'; // Nombre de la tabla

    protected $primaryKey = 'id_prod'; // Clave primaria

    protected $fillable = [
        'nom_prod',
        'descr',
        'prec',
        'img',
        'estatus',
        'id_cat',
    ];

    public $timestamps = false; // Deshabilitar created_at y updated_at
}