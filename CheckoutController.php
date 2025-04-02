<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;

// Ensure the Venta model exists in the App\Models namespace
use App\Models\DetalleVenta;
use App\Models\Inventario;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'id_usuario' => 'required|integer',
            'productos' => 'required|array',
            'productos.*.id_prod' => 'required|integer',
            'productos.*.cantidad' => 'required|integer|min:1',
            'metodo_pago' => 'required|string|max:50',
        ]);

        DB::beginTransaction();

        try {
            // Crear la venta
            $venta = Venta::create([
                'id_usuario' => $validated['id_usuario'],
                'fecha' => now(),
                'iva' => 0.16, // IVA fijo del 16%
                'imp_tot' => 0, // Se calculará más adelante
            ]);

            $total = 0;

            // Registrar los productos en detalle_venta y actualizar inventario
            foreach ($validated['productos'] as $producto) {
                $inventario = Inventario::where('id_prod', $producto['id_prod'])->first();

                if (!$inventario || $inventario->cant_disp < $producto['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto ID: {$producto['id_prod']}");
                }

                // Calcular el subtotal usando la relación producto
                $subtotal = $producto['cantidad'] * $inventario->producto->prec;
                $total += $subtotal;

                // Registrar en detalle_venta
                DetalleVenta::create([
                    'id_inv' => $inventario->id_inv, // ID del inventario
                    'id_venta' => $venta->id_venta,  // ID de la venta
                    'cant_comp' => $producto['cantidad'], // Cantidad comprada
                ]);

                // Actualizar inventario
                $inventario->update(['cant_disp' => $inventario->cant_disp - $producto['cantidad']]);
            }

            // Actualizar el total de la venta
            $venta->update([
                'imp_tot' => $total + ($total * $venta->iva),
            ]);

            // Registrar el pago
            Pago::create([
                'id_venta' => $venta->id_venta,
                'monto' => $venta->imp_tot,
                'metodo_pago' => $validated['metodo_pago'],
            ]);

            DB::commit();

            return response()->json(['mensaje' => 'Compra realizada con éxito'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}