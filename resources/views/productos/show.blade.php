@extends('layouts.app')

@section('titulo', $producto->nombre)

@section('contenido')
    <div class="tarjeta">
        <div class="barra-titulo">
            <h1>{{ $producto->nombre }}</h1>
            <div class="acciones">
                @can('comprar-productos')
                    @if ($producto->existencia > 0)
                        <a href="{{ route('ventas.create', ['producto' => $producto->id]) }}" class="btn ok">Comprar</a>
                    @endif
                @endcan
                @can('update', $producto)
                    <a href="{{ route('productos.edit', $producto) }}" class="btn">Editar</a>
                @endcan
            </div>
        </div>

        <dl class="datos">
            <dt>Descripción</dt><dd>{{ $producto->descripcion ?: '—' }}</dd>
            <dt>Precio</dt><dd>${{ number_format($producto->precio, 2) }}</dd>
            <dt>Existencia</dt><dd>{{ $producto->existencia }}</dd>
            <dt>Vendedor</dt><dd>{{ $producto->usuario->nombre_completo }} ({{ $producto->usuario->correo }})</dd>
            <dt>Categorías</dt>
            <dd>
                @forelse ($producto->categorias as $categoria)
                    <a class="etiqueta" href="{{ route('categorias.show', $categoria) }}">{{ $categoria->nombre }}</a>
                @empty
                    —
                @endforelse
            </dd>
            <dt>Ventas</dt><dd>{{ $producto->ventas_count }}</dd>
        </dl>

        <p><a href="{{ route('productos.index') }}">← Volver a productos</a></p>
    </div>
@endsection
