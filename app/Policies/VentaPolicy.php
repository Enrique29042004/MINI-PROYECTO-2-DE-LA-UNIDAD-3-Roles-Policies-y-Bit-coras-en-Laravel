<?php

namespace App\Policies;

use App\Models\Usuario;
use App\Models\Venta;

/**
 * Cliente: compra productos (crea ventas) y ve sus propias compras.
 * Gerente: consulta todas las ventas.
 * Administrador: consulta, corrige y elimina ventas.
 */
class VentaPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return true;
    }

    public function view(Usuario $usuario, Venta $venta): bool
    {
        return $usuario->esAdministrador()
            || $usuario->esGerente()
            || (int) $venta->cliente_id === (int) $usuario->id
            || (int) $venta->vendedor_id === (int) $usuario->id;
    }

    public function create(Usuario $usuario): bool
    {
        return $usuario->esCliente();
    }

    public function update(Usuario $usuario, Venta $venta): bool
    {
        return $usuario->esAdministrador();
    }

    public function delete(Usuario $usuario, Venta $venta): bool
    {
        return $usuario->esAdministrador();
    }
}
