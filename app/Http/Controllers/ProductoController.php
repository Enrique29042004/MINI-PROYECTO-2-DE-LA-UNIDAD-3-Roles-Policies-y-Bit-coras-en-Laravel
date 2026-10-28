<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * La autorizacion NO usa if por rol: se delega a ProductoPolicy.
 * En store() y update() la policy se aplica dentro del FormRequest (authorize()).
 */
class ProductoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Producto::class);

        $productos = Producto::with(['usuario', 'categorias'])->orderBy('nombre')->get();

        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $this->authorize('create', Producto::class);

        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.create', compact('categorias'));
    }

    public function store(StoreProductoRequest $request): RedirectResponse
    {
        $datos = $request->safe()->except('categorias');

        // El usuario autenticado es el vendedor del producto.
        $producto = Producto::create($datos + ['usuario_id' => Auth::id()]);

        $producto->categorias()->sync($request->validated('categorias', []));

        Log::channel('productos')->info('Producto creado', [
            'usuario_id' => Auth::id(),
            'producto_id' => $producto->id,
            'nombre' => $producto->nombre,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('productos.index')->with('exito', 'Producto creado correctamente.');
    }

    public function show(Producto $producto)
    {
        $this->authorize('view', $producto);

        $producto->load(['usuario', 'categorias'])->loadCount('ventas');

        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $this->authorize('update', $producto);

        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(UpdateProductoRequest $request, Producto $producto): RedirectResponse
    {
        $producto->update($request->safe()->except('categorias'));

        $producto->categorias()->sync($request->validated('categorias', []));

        Log::channel('productos')->info('Producto editado', [
            'usuario_id' => Auth::id(),
            'producto_id' => $producto->id,
            'cambios' => $producto->getChanges(),
            'ip' => $request->ip(),
        ]);

        return redirect()->route('productos.index')->with('exito', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $this->authorize('delete', $producto);

        // Se conserva el historial: no se borra un producto que ya se vendio.
        if ($producto->ventas()->exists()) {
            return back()->with('error', 'No se puede eliminar: el producto tiene ventas registradas.');
        }

        $producto->delete();

        Log::channel('productos')->info('Producto eliminado', [
            'usuario_id' => Auth::id(),
            'producto_id' => $producto->id,
            'nombre' => $producto->nombre,
            'ip' => request()->ip(),
        ]);

        return redirect()->route('productos.index')->with('exito', 'Producto eliminado correctamente.');
    }
}
