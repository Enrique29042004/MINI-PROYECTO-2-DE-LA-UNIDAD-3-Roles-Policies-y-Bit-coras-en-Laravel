<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Usuario del sistema. Hereda de Authenticatable para que Laravel pueda
 * autenticarlo (Auth::attempt, Auth::user, @can, Policies, etc.).
 */
class Usuario extends Authenticatable
{
    use HasFactory;

    public const ROL_ADMINISTRADOR = 'administrador';
    public const ROL_GERENTE = 'gerente';
    public const ROL_CLIENTE = 'cliente';

    public const ROLES = [
        self::ROL_ADMINISTRADOR,
        self::ROL_GERENTE,
        self::ROL_CLIENTE,
    ];

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'apellidos',
        'correo',
        'clave',
        'rol',
    ];

    protected $hidden = [
        'clave',
    ];

    protected function casts(): array
    {
        return [
            // Se guarda siempre como hash (si ya viene hasheada no la vuelve a hashear).
            'clave' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Adaptacion de Authenticatable a nuestros nombres de columna
    |--------------------------------------------------------------------------
    | Laravel espera una columna "password" y "remember_token". Aqui la
    | contrasena vive en "clave" y no usamos "recordarme".
    */

    public function getAuthPassword()
    {
        return $this->clave;
    }

    public function getAuthPasswordName()
    {
        return 'clave';
    }

    public function getRememberTokenName()
    {
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Ayudantes de rol
    |--------------------------------------------------------------------------
    */

    public function esAdministrador(): bool
    {
        return $this->rol === self::ROL_ADMINISTRADOR;
    }

    public function esGerente(): bool
    {
        return $this->rol === self::ROL_GERENTE;
    }

    public function esCliente(): bool
    {
        return $this->rol === self::ROL_CLIENTE;
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombre.' '.$this->apellidos);
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    /** Un usuario (vendedor) puede vender muchos productos. */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'usuario_id');
    }

    /** Compras realizadas por el usuario. */
    public function ventasComoCliente(): HasMany
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }

    /** Ventas realizadas por el usuario. */
    public function ventasComoVendedor(): HasMany
    {
        return $this->hasMany(Venta::class, 'vendedor_id');
    }

    /**
     * hasManyThrough: Usuario -> Productos -> (categoria_producto).
     *
     * Devuelve las filas de la tabla pivote de los productos del usuario.
     * (Una relacion muchos-a-muchos no se puede atravesar directamente con
     * hasManyThrough, por eso se atraviesa hasta el modelo pivote
     * CategoriaProducto, que a su vez pertenece a una Categoria.)
     */
    public function categoriasPorProductos(): HasManyThrough
    {
        return $this->hasManyThrough(
            CategoriaProducto::class, // modelo final
            Producto::class,          // modelo intermedio
            'usuario_id',             // FK en productos hacia usuarios
            'producto_id',            // FK en categoria_producto hacia productos
            'id',                     // PK local (usuarios)
            'id'                      // PK intermedia (productos)
        );
    }

    /**
     * Categorias (sin repetir) en las que el usuario tiene productos.
     * Uso: $usuario->categorias()->get()
     */
    public function categorias()
    {
        return Categoria::whereIn(
            'id',
            $this->categoriasPorProductos()->select('categoria_producto.categoria_id')
        )->orderBy('nombre');
    }
}
