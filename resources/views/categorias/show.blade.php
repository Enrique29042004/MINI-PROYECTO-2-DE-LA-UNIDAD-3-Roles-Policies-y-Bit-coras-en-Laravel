@extends('layouts.app')

@section('titulo', $categoria->nombre)

@section('contenido')
    <div class="tarjeta">
        <h1>Categoría: {{ $categoria->nombre }}</h1>
        <p class="meta">{{ $categoria->descripcion ?: 'Sin descripción.' }}</p>

        <h2>Productos en esta categoría ({{ $categoria->productos->count() }})</h2>

        <table>
            <thead>
                <tr><th>Producto</th><th>Vendedor</th><th>Precio</th><th>Existencia</th></tr>
            </thead>
            <tbody>
            @forelse ($categoria->productos as $producto)
                <tr>
                    <td><a href="{{ route('productos.show', $producto) }}">{{ $producto->nombre }}</a></td>
                    <td>{{ $producto->usuario->nombre_completo }}</td>
                    <td>${{ number_format($producto->precio, 2) }}</td>
                    <td>{{ $producto->existencia }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="meta">Esta categoría aún no tiene productos.</td></tr>
            @endforelse
            </tbody>
        </table>

        <p><a href="{{ route('categorias.index') }}">← Volver a categorías</a></p>
    </div>
@endsection
