<?php

namespace App\Policies;

use App\Models\Categoria;
use App\Models\Usuario;

class CategoriaPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return true;
    }

    public function view(Usuario $usuario, Categoria $categoria): bool
    {
        return true;
    }

    public function create(Usuario $usuario): bool
    {
        return $usuario->esAdministrador() || $usuario->esGerente();
    }

    public function update(Usuario $usuario, Categoria $categoria): bool
    {
        return $usuario->esAdministrador() || $usuario->esGerente();
    }

    public function delete(Usuario $usuario, Categoria $categoria): bool
    {
        return $usuario->esAdministrador() || $usuario->esGerente();
    }
}
