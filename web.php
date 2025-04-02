<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;


// Rutas para el administrador
Route::get('/validar_conexion', [LoginController::class, 'verificarConexion']);

// Rutas para el login
Route::post('/register', [LoginController::class, 'register']); // Registro de usuarios

route::post('/login', [LoginController::class, 'login']); // Inicio de sesión

route::post('/logout', [LoginController::class, 'logout']); // Cierre de sesión

// Rutas CRUD para productos
Route::get('/productos', [ProductoController::class, 'index']); // Obtener todos los productos

Route::post('/productos', [ProductoController::class, 'store']); // Agregar un producto

Route::put('/productos/{id}', [ProductoController::class, 'update']); // Actualizar un producto

Route::delete('/productos/{id}', [ProductoController::class, 'destroy']); // Eliminación lógica

Route::patch('/productos/{id}/toggle-status', [ProductoController::class, 'toggleStatus']);

Route::get('/productos/{id}', [ProductoController::class, 'show']);

Route::get('/producto/{id}', [ProductoController::class, 'show']);

// Rutas para las categorías
Route::get('/categorias', [CategoriaController::class, 'index']);

//Rutas para el inventario
Route::get('/inventarios', [InventarioController::class, 'index']); // Obtener todos los inventarios

Route::patch('/inventarios/{id}/increase', [InventarioController::class, 'increaseStock']); // Aumentar stock

Route::patch('/inventarios/{id}/decrease', [InventarioController::class, 'decreaseStock']); // Disminuir stock

Route::post('/inventarios', [InventarioController::class, 'store']); // Crear un nuevo inventario

//Rutas para single_product
Route::get('/productos/{id}/detalle', [ProductoController::class, 'showWithInventory']);

//Rutas para el carrito
Route::prefix('carrito')->group(function () {

    Route::get('/', [CarritoController::class, 'index']); // Ver carrito

    Route::post('/agregar', [CarritoController::class, 'add']); // Agregar producto al carrito

    Route::patch('/actualizar/{id}', [CarritoController::class, 'update']); // Actualizar cantidad de un producto

    Route::delete('/eliminar/{id}', [CarritoController::class, 'remove']); // Eliminar producto del carrito

    Route::delete('/vaciar', [CarritoController::class, 'clear']); // Vaciar el carrito

    Route::post('/comprar', [CarritoController::class, 'checkout']); // Proceder con la compra
    
});

Route::delete('/carrito/vaciar', [CarritoController::class, 'clear']);
Route::post('/carrito/agregar', [CarritoController::class, 'add']);
Route::post('/carrito', [CarritoController::class, 'add']);

// Rutas para el checkout
Route::post('/checkout', [CheckoutController::class, 'processCheckout']);