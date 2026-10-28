@extends('layouts.app')

@section('titulo', 'Productos')

@section('contenido')
    <div class="barra-titulo">
        <h1>Productos</h1>
        @can('create', \App\Models\Producto::class)
            <a href="{{ route('productos.create') }}" class="btn ok">+ Nuevo producto</a>
        @endcan
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Vendedor</th>
                <th>Categorías</th>
                <th>Precio</th>
                <th>Existencia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($productos as $producto)
            <tr>
                <td><a href="{{ route('productos.show', $producto) }}">{{ $producto->nombre }}</a></td>
                <td>{{ $producto->usuario->nombre_completo }}</td>
                <td>
                    @forelse ($producto->categorias as $categoria)
                        <span class="etiqueta">{{ $categoria->nombre }}</span>
                    @empty
                        <span class="meta">—</span>
                    @endforelse
                </td>
                <td>${{ number_format($producto->precio, 2) }}</td>
                <td>{{ $producto->existencia }}</td>
                <td>
                    <div class="acciones">
                        @can('comprar-productos')
                            @if ($producto->existencia > 0)
                                <a href="{{ route('ventas.create', ['producto' => $producto->id]) }}" class="btn ok">Comprar</a>
                            @else
                                <span class="meta">Agotado</span>
                            @endif
                        @endcan

                        @can('update', $producto)
                            <a href="{{ route('productos.edit', $producto) }}" class="btn">Editar</a>
                        @endcan

                        @can('delete', $producto)
                            <form method="POST" action="{{ route('productos.destroy', $producto) }}"
                                  onsubmit="return confirm('¿Eliminar este producto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn peligro">Eliminar</button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="meta">No hay productos registrados.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection
