<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

/**
 * Crea productos vendidos por el administrador y los gerentes, y los
 * relaciona con una o varias categorias (tabla pivote categoria_producto).
 * Requiere haber ejecutado antes UsuarioSeeder y CategoriaSeeder.
 */
class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $vendedores = Usuario::whereIn('rol', [Usuario::ROL_ADMINISTRADOR, Usuario::ROL_GERENTE])
            ->orderBy('id')
            ->get();

        if ($vendedores->isEmpty()) {
            $this->command?->warn('No hay vendedores. Ejecuta primero UsuarioSeeder.');

            return;
        }

        $categorias = Categoria::pluck('id', 'nombre');

        // [nombre, descripcion, precio, existencia, [categorias]]
        $productos = [
            ['Laptop 14"', 'Laptop ligera con 8 GB de RAM y SSD de 256 GB', 9999.00, 12, ['Electronica', 'Computacion']],
            ['Mouse inalambrico', 'Mouse optico inalambrico de 1600 DPI', 249.50, 40, ['Computacion', 'Oficina']],
            ['Teclado mecanico', 'Teclado mecanico en espanol con retroiluminacion', 899.00, 18, ['Computacion']],
            ['Audifonos Bluetooth', 'Audifonos con cancelacion de ruido', 1299.00, 25, ['Electronica']],
            ['Licuadora 600 W', 'Licuadora de vidrio con 3 velocidades', 780.00, 15, ['Hogar']],
            ['Balon de futbol', 'Balon numero 5 para cancha', 350.00, 30, ['Deportes']],
            ['Tenis para correr', 'Tenis ligeros para running', 1450.00, 20, ['Deportes']],
            ['Libro: Laravel desde cero', 'Guia practica de desarrollo web con Laravel', 520.00, 10, ['Libros', 'Computacion']],
            ['Silla ergonomica', 'Silla de oficina con soporte lumbar', 2300.00, 8, ['Oficina', 'Hogar']],
            ['Cuaderno profesional', 'Cuaderno de 100 hojas, cuadro grande', 45.00, 100, ['Oficina', 'Libros']],
            ['Bocina portatil', 'Bocina Bluetooth resistente al agua', 650.00, 22, ['Electronica', 'Hogar']],
            ['Memoria USB 64 GB', 'Memoria USB 3.0', 130.00, 60, ['Electronica', 'Computacion']],
        ];

        foreach ($productos as $indice => [$nombre, $descripcion, $precio, $existencia, $nombresCategorias]) {
            $producto = Producto::firstOrCreate(
                ['nombre' => $nombre],
                [
                    'descripcion' => $descripcion,
                    'precio' => $precio,
                    'existencia' => $existencia,
                    // Reparte los productos entre los vendedores disponibles.
                    'usuario_id' => $vendedores[$indice % $vendedores->count()]->id,
                ]
            );

            $ids = collect($nombresCategorias)
                ->map(fn ($nombreCategoria) => $categorias[$nombreCategoria] ?? null)
                ->filter()
                ->all();

            $producto->categorias()->syncWithoutDetaching($ids);
        }
    }
}
