@extends('layouts.app')

@section('titulo', $usuario->nombre_completo)

@section('contenido')
    <div class="tarjeta">
        <h1>{{ $usuario->nombre_completo }}</h1>

        <dl class="datos">
            <dt>Correo</dt><dd>{{ $usuario->correo }}</dd>
            <dt>Rol</dt><dd><span class="etiqueta">{{ $usuario->rol }}</span></dd>
        </dl>

        <h2>Productos que vende (hasMany)</h2>
        <table>
            <thead><tr><th>Producto</th><th>Precio</th><th>Categorías</th></tr></thead>
            <tbody>
            @forelse ($productos as $producto)
                <tr>
                    <td><a href="{{ route('productos.show', $producto) }}">{{ $producto->nombre }}</a></td>
                    <td>${{ number_format($producto->precio, 2) }}</td>
                    <td>
                        @foreach ($producto->categorias as $categoria)
                            <span class="etiqueta">{{ $categoria->nombre }}</span>
                        @endforeach
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="meta">Este usuario no vende productos.</td></tr>
            @endforelse
            </tbody>
        </table>

        <h2>Categorías a través de sus productos (hasManyThrough)</h2>
        <p>
            @forelse ($categorias as $categoria)
                <a class="etiqueta" href="{{ route('categorias.show', $categoria) }}">{{ $categoria->nombre }}</a>
            @empty
                <span class="meta">Sin categorías.</span>
            @endforelse
        </p>

        <p><a href="{{ route('usuarios.index') }}">← Volver a usuarios</a></p>
    </div>
@endsection
