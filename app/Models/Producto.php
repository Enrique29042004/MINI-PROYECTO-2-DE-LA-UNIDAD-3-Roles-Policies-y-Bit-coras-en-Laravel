<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'existencia',
        'usuario_id',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'existencia' => 'integer',
        ];
    }

    /** El vendedor del producto (usuario_id). */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /** Muchos a muchos con Categoria mediante la tabla pivote categoria_producto. */
    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(
            Categoria::class,
            'categoria_producto',
            'producto_id',
            'categoria_id'
        )->using(CategoriaProducto::class);
    }

    /** Un producto puede aparecer en muchas ventas. */
    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'producto_id');
    }
}
