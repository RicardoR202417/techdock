<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_usuario'; // Clave primaria personalizada

    protected $fillable = [
        'nombre',
        'correo',
        'usuario',
        'clave',
        'tipo_usuario',
        'token',
    ];

    public $timestamps = false; // Deshabilitar created_at y updated_at
}