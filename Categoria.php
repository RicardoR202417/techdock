<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categoria'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id_cat'; // Clave primaria de la tabla
    public $timestamps = false; // Deshabilitar timestamps si no existen en la tabla
}