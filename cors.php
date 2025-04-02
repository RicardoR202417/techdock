<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar los ajustes para compartir recursos entre orígenes
    | o "CORS". Esto determina qué operaciones entre orígenes pueden ejecutarse
    | en los navegadores web. Puedes ajustar estos ajustes según sea necesario.
    |
    | Para aprender más: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*', 
        'sanctum/csrf-cookie', // Para manejar autenticación y CSRF
        'login', 
        'logout', 
        'register',
        'productos',
        'productos/*',// Asegúrate de que el registro también esté permitido
        'categorias',
        'inventarios',
        'inventarios/*',
        'carrito',
        'carrito/*',
        'producto/*',
        '*'
    ], 

    'allowed_methods' => ['*'], // Permite todos los métodos HTTP (GET, POST, PUT, DELETE, etc.)

    // Permitir solicitudes desde 'localhost' con el puerto que estás utilizando (ajusta si es necesario)
    'allowed_origins' => [
        'http://localhost:8080', // Si tu frontend está en http://localhost
        'http://localhost:3000', // Si estás usando un puerto diferente para tu frontend
        'http://127.0.0.1:8000', // Si estás usando Laravel Sail
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Permite todos los encabezados, incluidos Authorization, Content-Type, etc.

    'exposed_headers' => [], // No es necesario exponer ningún encabezado adicional para este caso

    'max_age' => 0, // Tiempo en segundos para el que se guarda la respuesta de preflight

    // Cambia a true si necesitas soporte para credenciales (cookies o tokens)
    'supports_credentials' => true,

];