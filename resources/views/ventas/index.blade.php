@extends('layouts.app')

@section('titulo', 'Ventas')

@section('contenido')
    <div class="barra-titulo">
        <h1>Ventas</h1>
        @can('create', \App\Models\Venta::class)
            <a href="{{ route('ventas.create') }}" class="btn ok">+ Comprar producto</a>
        @endcan
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($ventas as $venta)
            <tr>
                <td>{{ $venta->id }}</td>
                <td>{{ $venta->fecha->format('d/m/Y') }}</td>
                <td>{{ $venta->producto->nombre }}</td>
                <td>{{ $venta->cliente->nombre_completo }}</td>
                <td>{{ $venta->vendedor->nombre_completo }}</td>
                <td>${{ number_format($venta->total, 2) }}</td>
                <td>
                    <div class="acciones">
                        @can('view', $venta)
                            <a href="{{ route('ventas.show', $venta) }}" class="btn sec">Ver</a>
                        @endcan
                        @can('update', $venta)
                            <a href="{{ route('ventas.edit', $venta) }}" class="btn">Editar</a>
                        @endcan
                        @can('delete', $venta)
                            <form method="POST" action="{{ route('ventas.destroy', $venta) }}"
                                  onsubmit="return confirm('¿Eliminar esta venta? El producto regresará al inventario.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn peligro">Eliminar</button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="meta">No hay ventas registradas.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection
