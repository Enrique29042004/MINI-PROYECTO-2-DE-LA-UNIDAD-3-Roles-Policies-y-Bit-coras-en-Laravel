<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Database\Seeder;

/**
 * Registra algunas ventas de ejemplo (cliente -> producto -> vendedor).
 * Si ya existen ventas no hace nada, para poder ejecutarlo varias veces.
 */
class VentaSeeder extends Seeder
{
    public function run(): void
    {
        if (Venta::exists()) {
            return;
        }

        $clientes = Usuario::where('rol', Usuario::ROL_CLIENTE)->orderBy('id')->get();
        $productos = Producto::where('existencia', '>', 5)->orderBy('id')->get();

        if ($clientes->isEmpty() || $productos->isEmpty()) {
            return;
        }

        foreach ($productos->take(6) as $indice => $producto) {
            $cliente = $clientes[$indice % $clientes->count()];

            Venta::create([
                'producto_id' => $producto->id,
                'vendedor_id' => $producto->usuario_id,
                'cliente_id' => $cliente->id,
                'fecha' => now()->subDays($indice)->toDateString(),
                'total' => $producto->precio,
            ]);

            $producto->decrement('existencia');
        }
    }
}
