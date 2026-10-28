<?php

namespace App\Providers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use App\Policies\CategoriaPolicy;
use App\Policies\ProductoPolicy;
use App\Policies\UsuarioPolicy;
use App\Policies\VentaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Policies (una por entidad)
        Gate::policy(Usuario::class, UsuarioPolicy::class);
        Gate::policy(Producto::class, ProductoPolicy::class);
        Gate::policy(Categoria::class, CategoriaPolicy::class);
        Gate::policy(Venta::class, VentaPolicy::class);

        // Gates (permisos que no pertenecen a un modelo concreto)

        // Solo el Administrador puede asignar o cambiar roles.
        Gate::define('asignar-rol', fn (Usuario $usuario): bool => $usuario->esAdministrador());

        // Solo el Cliente puede comprar productos.
        Gate::define('comprar-productos', fn (Usuario $usuario): bool => $usuario->esCliente());
    }
}
