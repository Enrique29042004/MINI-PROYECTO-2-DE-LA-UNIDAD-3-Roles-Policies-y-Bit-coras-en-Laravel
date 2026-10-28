<?php

namespace App\Policies;

use App\Models\Usuario;

/**
 * Administrador: crear, editar y eliminar usuarios.
 * Gerente: editar clientes (nunca gerentes ni administradores).
 * Cliente: sin acceso a la administracion de usuarios.
 */
class UsuarioPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return $usuario->esAdministrador() || $usuario->esGerente();
    }

    public function view(Usuario $usuario, Usuario $modelo): bool
    {
        return $this->viewAny($usuario);
    }

    public function create(Usuario $usuario): bool
    {
        return $usuario->esAdministrador();
    }

    public function update(Usuario $usuario, Usuario $modelo): bool
    {
        if ($usuario->esAdministrador()) {
            return true;
        }

        return $usuario->esGerente() && $modelo->esCliente();
    }

    public function delete(Usuario $usuario, Usuario $modelo): bool
    {
        // El administrador puede eliminar a cualquiera, excepto a si mismo.
        return $usuario->esAdministrador() && $usuario->isNot($modelo);
    }
}
