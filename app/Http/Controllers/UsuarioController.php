<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

/**
 * Administrador: crea, edita y elimina usuarios.
 * Gerente: solo edita clientes. (Reglas en UsuarioPolicy.)
 */
class UsuarioController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Usuario::class);

        $usuarios = Usuario::orderBy('rol')->orderBy('nombre')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $this->authorize('create', Usuario::class);

        return view('usuarios.create');
    }

    public function store(StoreUsuarioRequest $request): RedirectResponse
    {
        $usuario = Usuario::create($request->validated());

        Log::info('Usuario creado', [
            'creado_por' => Auth::id(),
            'usuario_id' => $usuario->id,
            'rol' => $usuario->rol,
        ]);

        return redirect()->route('usuarios.index')->with('exito', 'Usuario creado correctamente.');
    }

    public function show(Usuario $usuario)
    {
        $this->authorize('view', $usuario);

        // Ejemplo de hasMany y hasManyThrough: productos del usuario y las
        // categorias en las que vende (Usuario -> Productos -> Categorias).
        $productos = $usuario->productos()->with('categorias')->orderBy('nombre')->get();
        $categorias = $usuario->categorias()->get();

        return view('usuarios.show', compact('usuario', 'productos', 'categorias'));
    }

    public function edit(Usuario $usuario)
    {
        $this->authorize('update', $usuario);

        return view('usuarios.edit', compact('usuario'));
    }

    public function update(UpdateUsuarioRequest $request, Usuario $usuario): RedirectResponse
    {
        $datos = $request->validated();

        // Clave vacia = conservar la actual.
        if (empty($datos['clave'])) {
            unset($datos['clave']);
        }

        // Evita que un gerente ascienda a un cliente: solo quien pasa el Gate cambia el rol.
        if (! Gate::allows('asignar-rol')) {
            unset($datos['rol']);
        }

        $usuario->update($datos);

        Log::info('Usuario editado', [
            'editado_por' => Auth::id(),
            'usuario_id' => $usuario->id,
        ]);

        return redirect()->route('usuarios.index')->with('exito', 'Usuario actualizado correctamente.');
    }

    public function destroy(Usuario $usuario): RedirectResponse
    {
        $this->authorize('delete', $usuario);

        $tieneVentas = $usuario->ventasComoCliente()->exists() || $usuario->ventasComoVendedor()->exists();

        if ($tieneVentas) {
            return back()->with('error', 'No se puede eliminar: el usuario tiene ventas registradas.');
        }

        $usuario->delete();

        Log::info('Usuario eliminado', [
            'eliminado_por' => Auth::id(),
            'usuario_id' => $usuario->id,
        ]);

        return redirect()->route('usuarios.index')->with('exito', 'Usuario eliminado correctamente.');
    }
}
