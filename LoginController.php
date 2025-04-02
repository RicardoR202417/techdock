<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
     public function verificarConexion(){
        try{
            //cosultamos que la conbeccion a la bd fue exitosa
            DB::connection()->getPdo();
            return response()->json([
                "estatus"=>"exitoso",
                "mensaje"=>"Conexión al la base de datos establecida exitosamente"
            ], 200);
        }catch(Exception $e){
            return response()->json([
                "estatus"=>"error",
                "mensaje"=>"Error en la Conexión", $e->getMessage()
            ]. 500);
        }
    }

    // Ya no hay vistas
    //public function loginForm(){
        // Retornamos la vista

      //  return view('authenticate.login');
    //}

    // Funcion para procesar el login
    // Request $request para obtener datos de un form


    public function login(Request $request)
    {
        try {
            // Validar los datos de entrada
            $request->validate([
                'usuario' => 'required|string',
                'clave' => 'required|string',
            ]);

            // Buscar el usuario por su nombre de usuario
            $usuario = Usuario::where('usuario', $request->usuario)->first();

            // Verificar si el usuario existe y la contraseña es correcta
            if ($usuario && Hash::check($request->clave, $usuario->clave)) {
                // Generar un token
                $token = Str::random(60);
                $usuario->token = $token;
                $usuario->save();

                return response()->json([
                    "estatus" => "exitoso",
                    "mensaje" => "Inicio de sesión exitoso",
                    "usuario" => [
                        "id_usuario" => $usuario->id_usuario,
                        "nombre" => $usuario->nombre,
                        "tipo_usuario" => $usuario->tipo_usuario,
                    ],
                    "token" => $token,
                ], 200);
            } else {
                return response()->json([
                    "estatus" => "error",
                    "mensaje" => "Credenciales incorrectas",
                ], 401);
            }
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error($e->getMessage());

            return response()->json([
                "estatus" => "error",
                "mensaje" => "Error en el servidor",
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->header('Authorization');

            $usuario = Usuario::where('token', str_replace('Bearer ','', $token))->first();

            if($usuario){
                $usuario->token = null;
                $usuario->save();

                return response()->json([
                    "estatus" => "exitoso",
                    "mensaje" => "Sesión cerrada correctamente"
                ], 200);
            }else{
                return response()->json([
                    "estatus" => "error",
                    "mensaje" => "El token proporcionado es incorrecto. Sesión no cerrada."
                ], 401);
            }

        } catch (Exception $e) {
            return response()->json([
                "estatus" => "error",
                "mensaje" => "Error en el servidor: " . $e->getMessage()
            ], 500);
        }

    }





    public function usuarioIndex(){
        // Retornamos la vista
        return view('usuario');
    }

    public function adminIndex(){
        // Retornamos la vista
        return view('admin');
}

public function register(Request $request)
{
    try {
        // Validar los datos de entrada
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'usuario' => 'required|string|unique:usuarios,usuario|max:50',
            'clave' => 'required|string', // Sin restricción de longitud mínima
        ]);

        // Crear un nuevo usuario
        $usuario = new Usuario();
        $usuario->nombre = $request->nombre;
        $usuario->correo = $request->correo;
        $usuario->usuario = $request->usuario;
        $usuario->clave = Hash::make($request->clave); // Encriptar la contraseña
        $usuario->tipo_usuario = 2; // Asignar un tipo de usuario por defecto
        $usuario->save();

        // Responder con éxito
        return response()->json([
            "estatus" => "exitoso",
            "mensaje" => "Registro exitoso",
            "usuario" => [
                "id_usuario" => $usuario->id_usuario,
                "nombre" => $usuario->nombre,
                "tipo_usuario" => $usuario->tipo_usuario
            ],
        ], 201);
    } catch (Exception $e) {
        return response()->json([
            "estatus" => "error",
            "mensaje" => "Error en el servidor: " . $e->getMessage()
        ], 500);
 }
}

}