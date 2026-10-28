<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Modelo de la tabla pivote categoria_producto.
 * Permite atravesarla con hasManyThrough desde Usuario.
 */
class CategoriaProducto extends Pivot
{
    protected $table = 'categoria_producto';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
