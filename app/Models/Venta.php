<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $fillable = [
        'producto_id',
        'vendedor_id',
        'cliente_id',
        'fecha',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'total' => 'decimal:2',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /** Quien compra. */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    /** Quien vende. */
    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'vendedor_id');
    }

    /**
     * Ventas que un usuario puede listar: el cliente solo ve sus compras,
     * administrador y gerente ven todas. (Es un filtro de consulta; la
     * autorizacion de cada accion la deciden las Policies.)
     */
    public function scopeVisiblesPara(Builder $consulta, Usuario $usuario): Builder
    {
        return $usuario->esCliente()
            ? $consulta->where('cliente_id', $usuario->id)
            : $consulta;
    }
}
