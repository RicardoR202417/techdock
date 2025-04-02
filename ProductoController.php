<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    // Obtener todos los productos (incluyendo los activos para el cliente)
    public function index(Request $request)
    {
        $query = Producto::query();

        // Filtrar por categoría si se proporciona el parámetro `id_cat`
        if ($request->has('id_cat') && $request->id_cat != '') {
            $query->where('id_cat', $request->id_cat);
        }

        // Filtrar solo productos activos si se proporciona el parámetro `only_active`
        if ($request->has('only_active') && $request->only_active == 'true') {
            $query->where('estatus', 1);
        }

        $productos = $query->get();

        return response()->json($productos, 200);
    }

    // Agregar un nuevo producto
    public function store(Request $request)
    {
        $request->validate([
            'nom_prod' => 'required|string|max:255',
            'descr' => 'nullable|string|max:500',
            'prec' => 'required|numeric|min:0',
            'img' => 'nullable|string|max:255',
            'id_cat' => 'required|integer|exists:categoria,id_cat',
        ]);

        $producto = Producto::create([
            'nom_prod' => $request->nom_prod,
            'descr' => $request->descr,
            'prec' => $request->prec,
            'img' => $request->img,
            'estatus' => 1, // Activo por defecto
            'id_cat' => $request->id_cat,
        ]);

        return response()->json([
            'estatus' => 'exitoso',
            'mensaje' => 'Producto agregado correctamente',
            'producto' => $producto,
        ], 201);
    }

    // Actualizar un producto existente
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nom_prod' => 'nullable|string|max:255',
            'descr' => 'nullable|string|max:500',
            'prec' => 'nullable|numeric|min:0',
            'img' => 'nullable|string|max:255',
            'id_cat' => 'nullable|integer|exists:categoria,id_cat',
        ]);

        $producto->update($request->all());

        return response()->json([
            'estatus' => 'exitoso',
            'mensaje' => 'Producto actualizado correctamente',
            'producto' => $producto,
        ], 200);
    }

    // Mostrar un producto específico
    public function show($id)
    {
        try {
            // Buscar el producto por ID
            $producto = Producto::findOrFail($id);

            // Devolver los detalles del producto como JSON
            return response()->json([
                'id_prod' => $producto->id_prod,
                'nom_prod' => $producto->nom_prod,
                'prec' => $producto->prec,
                'desc' => $producto->descr, // Asegúrate de usar el nombre correcto del campo
                'imagen' => $producto->img, // URL de la imagen
            ], 200);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error('Error al cargar los detalles del producto: ' . $e->getMessage());
            return response()->json(['mensaje' => 'Error interno del servidor'], 500);
        }
    }

    // Mostrar un producto específico con inventario
    public function showWithInventory($id)
    {
        $producto = Producto::with('inventario')->find($id);

        if (!$producto) {
            return response()->json([
                'estatus' => 'error',
                'mensaje' => 'Producto no encontrado',
            ], 404);
        }

        return response()->json($producto, 200);
    }

    // Eliminación lógica de un producto
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->estatus = 0; // Cambiar el estatus a inactivo
        $producto->save();

        return response()->json([
            'estatus' => 'exitoso',
            'mensaje' => 'Producto eliminado correctamente',
        ], 200);
    }

    // Alternar el estatus de un producto
    public function toggleStatus($id)
    {
        $producto = Producto::findOrFail($id);

        // Alternar el estatus
        $producto->estatus = $producto->estatus === 1 ? 0 : 1;
        $producto->save();

        return response()->json([
            'estatus' => 'exitoso',
            'mensaje' => $producto->estatus === 1 ? 'Producto activado' : 'Producto desactivado',
            'producto' => $producto,
        ], 200);
    }
}
