<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventarioController extends Controller
{
    // Obtener todos los inventarios
    public function index()
    {
        try {
            // Obtener todos los inventarios con la relación del producto
            $inventarios = Inventario::with('producto')->get();

            return response()->json($inventarios, 200);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error('Error al obtener los inventarios: ' . $e->getMessage());

            return response()->json([
                'estatus' => 'error',
                'mensaje' => 'Ocurrió un error al obtener los inventarios.',
            ], 500);
        }
    }

    // Aumentar el stock de un producto
    public function increaseStock(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $inventario = Inventario::findOrFail($id);
        $inventario->cant_disp += $request->cantidad;
        $inventario->save();

        return response()->json([
            'estatus' => 'exitoso',
            'mensaje' => 'Stock aumentado correctamente',
            'inventario' => $inventario,
        ], 200);
    }

    // Disminuir el stock de un producto
    public function decreaseStock(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $inventario = Inventario::findOrFail($id);

        if ($inventario->cant_disp < $request->cantidad) {
            return response()->json([
                'estatus' => 'error',
                'mensaje' => 'No hay suficiente stock para disminuir',
            ], 400);
        }

        $inventario->cant_disp -= $request->cantidad;
        $inventario->save();

        return response()->json([
            'estatus' => 'exitoso',
            'mensaje' => 'Stock disminuido correctamente',
            'inventario' => $inventario,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_prod' => 'required|exists:producto,id_prod',
            'cant_disp' => 'required|integer|min:1',
        ]);

        // Verificar si ya existe un inventario para el producto
        $inventario = Inventario::where('id_prod', $request->id_prod)->first();

        if ($inventario) {
            // Si ya existe, actualizar la cantidad disponible
            $inventario->cant_disp += $request->cant_disp;
            $inventario->save();

            return response()->json([
                'estatus' => 'exitoso',
                'mensaje' => 'Inventario actualizado correctamente',
                'inventario' => $inventario,
            ], 200);
        } else {
            // Si no existe, crear un nuevo inventario
            $inventario = Inventario::create([
                'id_prod' => $request->id_prod,
                'cant_disp' => $request->cant_disp,
            ]);

            return response()->json([
                'estatus' => 'exitoso',
                'mensaje' => 'Inventario agregado correctamente',
                'inventario' => $inventario,
            ], 201);
        }
    }
}