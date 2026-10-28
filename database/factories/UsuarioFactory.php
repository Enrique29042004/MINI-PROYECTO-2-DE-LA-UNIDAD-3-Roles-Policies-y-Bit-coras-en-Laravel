<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public const NOMBRES = ['Juan', 'Mario', 'Maria', 'Pedro'];

    public const APELLIDOS = ['Lopez', 'Sanchez', 'Hernandez', 'Martinez'];

    public function definition()
    {
        $nombres = self::NOMBRES;

        $apellidos = self::APELLIDOS;

        $nombre = $this->faker->randomElement($nombres);

        $apellido = $this->faker->randomElement($apellidos);

        // La contrasena siempre es "123" guardada como hash. Se calcula una sola
        // vez por ejecucion porque bcrypt es lento y el valor es el mismo.
        static $clave = null;
        $clave ??= Hash::make('123');

        return [

            'nombre' => $nombre,

            'apellidos' => $apellido,

            'correo' => self::correoPara($nombre, $apellido),

            'clave' => $clave,

            'rol' => $this->faker->randomElement(['cliente', 'gerente']),

        ];
    }

    /** Formato: inicial del nombre + apellido, en minusculas. Ej: jlopez@tuxtla.tecnm.mx */
    public static function correoPara(string $nombre, string $apellido): string
    {
        return strtolower(substr($nombre, 0, 1).$apellido).'@tuxtla.tecnm.mx';
    }

    /** Fija nombre y apellido (y el correo que les corresponde). */
    public function conNombre(string $nombre, string $apellido): static
    {
        return $this->state(fn () => [
            'nombre' => $nombre,
            'apellidos' => $apellido,
            'correo' => self::correoPara($nombre, $apellido),
        ]);
    }

    public function administrador(): static
    {
        return $this->state(fn () => ['rol' => Usuario::ROL_ADMINISTRADOR]);
    }

    public function gerente(): static
    {
        return $this->state(fn () => ['rol' => Usuario::ROL_GERENTE]);
    }

    public function cliente(): static
    {
        return $this->state(fn () => ['rol' => Usuario::ROL_CLIENTE]);
    }
}
