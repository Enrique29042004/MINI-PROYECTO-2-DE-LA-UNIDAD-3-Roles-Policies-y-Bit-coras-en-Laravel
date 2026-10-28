<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Database\Factories\UsuarioFactory;
use Illuminate\Database\Seeder;

/**
 * Crea usuarios con UsuarioFactory usando unicamente los nombres y apellidos
 * permitidos. Contrasena de todos: 123
 *
 * Nota: "Mario" y "Maria" comparten inicial, por lo que Mario Martinez y
 * Maria Martinez generarian el mismo correo (mmartinez@...). Como el correo es
 * unico, el seeder omite las combinaciones cuyo correo ya existe.
 */
class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // [nombre, apellido, rol]
        $usuarios = [
            ['Juan', 'Lopez', Usuario::ROL_ADMINISTRADOR],
            ['Mario', 'Sanchez', Usuario::ROL_GERENTE],
            ['Pedro', 'Hernandez', Usuario::ROL_GERENTE],
            ['Maria', 'Martinez', Usuario::ROL_CLIENTE],
            ['Juan', 'Hernandez', Usuario::ROL_CLIENTE],
            ['Pedro', 'Lopez', Usuario::ROL_CLIENTE],
            ['Maria', 'Sanchez', Usuario::ROL_CLIENTE],
            ['Mario', 'Lopez', Usuario::ROL_CLIENTE],
            ['Juan', 'Martinez', Usuario::ROL_CLIENTE],
        ];

        foreach ($usuarios as [$nombre, $apellido, $rol]) {
            $correo = UsuarioFactory::correoPara($nombre, $apellido);

            if (Usuario::where('correo', $correo)->exists()) {
                continue;
            }

            Usuario::factory()
                ->conNombre($nombre, $apellido)
                ->state(['rol' => $rol])
                ->create();
        }
    }
}
