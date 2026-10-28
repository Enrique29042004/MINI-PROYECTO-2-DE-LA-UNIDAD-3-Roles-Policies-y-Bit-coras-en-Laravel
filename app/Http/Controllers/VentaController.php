<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVentaRequest;
use App\Http\Requests\UpdateVentaRequest;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VentaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Venta::class);

        $ventas = Venta::visiblesPara(Auth::user())
            ->with(['producto', 'cliente', 'vendedor'])
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();

        return view('ventas.index', compact('ventas'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Venta::class);

        $productos = Producto::with('usuario')
            ->where('existencia', '>', 0)
            ->orderBy('nombre')
            ->get();

        $seleccionado = $request->integer('producto');

        return view('ventas.create', compact('productos', 'seleccionado'));
    }

    public function store(StoreVentaRequest $request): RedirectResponse
    {
        $venta = DB::transaction(function () use ($request) {
            // Bloquea la fila para que dos compras simultaneas no vendan la ultima pieza dos veces.
            $producto = Producto::lockForUpdate()->findOrFail($request->validated('producto_id'));

            if ($producto->existencia < 1) {
                return null;
            }

            $venta = Venta::create([
                'producto_id' => $producto->id,
                'vendedor_id' => $producto->usuario_id,   // el vendedor es dueno del producto
                'cliente_id' => Auth::id(),               // el cliente es quien compra
                'fecha' => $request->validated('fecha') ?? now()->toDateString(),
                'total' => $producto->precio,
            ]);

            $producto->decrement('existencia');

            return $venta;
        });

        if ($venta === null) {
            return back()->withErrors(['producto_id' => 'El producto ya no tiene existencia.'])->withInput();
        }

        Log::channel('ventas')->info('Venta creada', [
            'venta_id' => $venta->id,
            'producto_id' => $venta->producto_id,
            'cliente_id' => $venta->cliente_id,
            'vendedor_id' => $venta->vendedor_id,
            'total' => $venta->total,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('ventas.show', $venta)->with('exito', 'Compra registrada correctamente.');
    }

    public function show(Venta $venta)
    {
        $this->authorize('view', $venta);

        $venta->load(['producto', 'cliente', 'vendedor']);

        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $this->authorize('update', $venta);

        $venta->load(['producto', 'cliente', 'vendedor']);

        return view('ventas.edit', compact('venta'));
    }

    public function update(UpdateVentaRequest $request, Venta $venta): RedirectResponse
    {
        $venta->update($request->validated());

        Log::channel('ventas')->info('Venta editada', [
            'venta_id' => $venta->id,
            'usuario_id' => Auth::id(),
            'cambios' => $venta->getChanges(),
            'ip' => $request->ip(),
        ]);

        return redirect()->route('ventas.show', $venta)->with('exito', 'Venta actualizada correctamente.');
    }

    public function destroy(Venta $venta): RedirectResponse
    {
        $this->authorize('delete', $venta);

        DB::transaction(function () use ($venta) {
            // Al cancelar la venta, la pieza regresa al inventario.
            Producto::whereKey($venta->producto_id)->increment('existencia');
            $venta->delete();
        });

        Log::channel('ventas')->info('Venta eliminada', [
            'venta_id' => $venta->id,
            'usuario_id' => Auth::id(),
            'ip' => request()->ip(),
        ]);

        return redirect()->route('ventas.index')->with('exito', 'Venta eliminada correctamente.');
    }
}
