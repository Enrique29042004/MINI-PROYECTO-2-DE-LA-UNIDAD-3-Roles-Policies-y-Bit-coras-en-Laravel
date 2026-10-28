<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['Electronica', 'Dispositivos y accesorios electronicos'],
            ['Computacion', 'Equipos de computo, perifericos y componentes'],
            ['Hogar', 'Articulos para el hogar y la cocina'],
            ['Deportes', 'Equipo y ropa deportiva'],
            ['Libros', 'Libros impresos y material de estudio'],
            ['Oficina', 'Papeleria y mobiliario de oficina'],
        ];

        foreach ($categorias as [$nombre, $descripcion]) {
            Categoria::firstOrCreate(
                ['nombre' => $nombre],
                ['descripcion' => $descripcion]
            );
        }
    }
}
