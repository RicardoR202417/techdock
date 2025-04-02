<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\CarritoProducto;
use App\Models\Producto;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Ensure this line is present to use the Log facade

class CarritoController extends Controller
{
    // Ver carrito
    public function index(Request $request)
    {
        try {
            $id_usuario = $request->query('id_usuario');
            $carrito = Carrito::where('id_usuario', $id_usuario)->first();

            if (!$carrito) {
                return response()->json(['mensaje' => 'El carrito está vacío'], 200);
            }

            $productos = $carrito->productos()->with('producto')->get();
            $subtotal = $productos->sum(fn($item) => $item->producto->prec * $item->cantidad);
            $iva = $subtotal * 0.16;
            $total = $subtotal + $iva;

            return response()->json([
                'productos' => $productos,
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener el carrito: ' . $e->getMessage());
            return response()->json(['mensaje' => 'Error interno del servidor'], 500);
        }
    }

    // Agregar producto al carrito
    public function add(Request $request)
    {
        try {
            // Registrar los datos recibidos para depuración
            Log::info('Datos recibidos en add:', $request->all());

            // Validar los datos de la solicitud
            $request->validate([
                'id_usuario' => 'required|exists:usuarios,id_usuario',
                'id_prod' => 'required|exists:producto,id_prod',
                'cantidad' => 'required|integer|min:1',
            ]);

            // Obtener el inventario del producto
            $inventario = Inventario::where('id_prod', $request->id_prod)->first();
            if (!$inventario) {
                return response()->json(['mensaje' => 'Producto no disponible en inventario'], 400);
            }

            // Verificar si la cantidad solicitada excede el stock disponible
            $carrito = Carrito::firstOrCreate(['id_usuario' => $request->id_usuario]);
            $carritoProducto = CarritoProducto::where('id_carrito', $carrito->id)
                ->where('id_producto', $request->id_prod)
                ->first();

            $cantidadEnCarrito = $carritoProducto ? $carritoProducto->cantidad : 0;
            $cantidadTotal = $cantidadEnCarrito + $request->cantidad;

            if ($cantidadTotal > $inventario->cant_disp) {
                return response()->json([
                    'mensaje' => 'La cantidad solicitada excede el stock disponible. Stock actual: ' . $inventario->cant_disp,
                ], 400);
            }

            // Incrementar la cantidad del producto en el carrito
            if ($carritoProducto) {
                $carritoProducto->cantidad += $request->cantidad;
            } else {
                $carritoProducto = new CarritoProducto([
                    'id_carrito' => $carrito->id,
                    'id_producto' => $request->id_prod,
                    'cantidad' => $request->cantidad,
                ]);
            }
            $carritoProducto->save();

            return response()->json(['mensaje' => 'Producto agregado al carrito'], 200);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error('Error al agregar producto al carrito: ' . $e->getMessage());
            return response()->json(['mensaje' => 'Error interno del servidor'], 500);
        }
    }

    // Actualizar cantidad de un producto
    public function update(Request $request, $id)
    {
        try {
            // Validar la cantidad enviada
            $request->validate(['cantidad' => 'required|integer|min:1']);

            // Obtener el producto del carrito
            $carritoProducto = CarritoProducto::findOrFail($id);

            // Obtener el inventario del producto
            $inventario = Inventario::where('id_prod', $carritoProducto->id_producto)->first();
            if (!$inventario) {
                return response()->json(['mensaje' => 'Producto no disponible en inventario'], 400);
            }

            // Verificar si la cantidad actualizada excede el stock disponible
            if ($request->cantidad > $inventario->cant_disp) {
                return response()->json([
                    'mensaje' => 'La cantidad solicitada excede el stock disponible. Stock actual: ' . $inventario->cant_disp,
                ], 400);
            }

            // Actualizar la cantidad del producto en el carrito
            $carritoProducto->cantidad = $request->cantidad;
            $carritoProducto->save();

            return response()->json(['mensaje' => 'Cantidad actualizada'], 200);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error('Error al actualizar la cantidad del producto en el carrito: ' . $e->getMessage());
            return response()->json(['mensaje' => 'Error interno del servidor'], 500);
        }
    }

    // Eliminar producto del carrito
    public function remove($id)
    {
        $carritoProducto = CarritoProducto::findOrFail($id);
        $carritoProducto->delete();

        return response()->json(['mensaje' => 'Producto eliminado del carrito'], 200);
    }

    // Vaciar carrito
    public function clear(Request $request)
    {
        $id_usuario = $request->query('id_usuario');
        $carrito = Carrito::where('id_usuario', $id_usuario)->first();

        if ($carrito) {
            $carrito->productos()->delete();
        }

        return response()->json(['mensaje' => 'Carrito vaciado'], 200);
    }

    // Proceder con la compra 
    public function checkout(Request $request)
    {
        try {
            $carrito = Carrito::where('id_usuario', $request->id_usuario)->first();

            if (!$carrito || $carrito->productos()->count() === 0) {
                return response()->json(['mensaje' => 'El carrito está vacío'], 400);
            }

            foreach ($carrito->productos as $carritoProducto) {
                $inventario = Inventario::where('id_prod', $carritoProducto->id_producto)->first();

                if (!$inventario || $inventario->cant_disp < $carritoProducto->cantidad) {
                    return response()->json([
                        'mensaje' => 'No hay suficiente stock para el producto: ' . $carritoProducto->producto->nom_prod,
                    ], 400);
                }

                // Disminuir el stock disponible
                $inventario->cant_disp -= $carritoProducto->cantidad;
                $inventario->save();
            }

            // Vaciar el carrito después de la compra
            $carrito->productos()->delete();

            return response()->json(['mensaje' => 'Compra realizada con éxito'], 200);
        } catch (\Exception $e) {
            Log::error('Error al realizar la compra: ' . $e->getMessage());
            return response()->json(['mensaje' => 'Error interno del servidor'], 500);
        }
    }
}