@extends('layouts.app')

@section('titulo', 'Venta #'.$venta->id)

@section('contenido')
    <div class="tarjeta">
        <h1>Venta #{{ $venta->id }}</h1>

        <dl class="datos">
            <dt>Fecha</dt><dd>{{ $venta->fecha->format('d/m/Y') }}</dd>
            <dt>Producto</dt><dd><a href="{{ route('productos.show', $venta->producto) }}">{{ $venta->producto->nombre }}</a></dd>
            <dt>Cliente</dt><dd>{{ $venta->cliente->nombre_completo }} ({{ $venta->cliente->correo }})</dd>
            <dt>Vendedor</dt><dd>{{ $venta->vendedor->nombre_completo }} ({{ $venta->vendedor->correo }})</dd>
            <dt>Total</dt><dd>${{ number_format($venta->total, 2) }}</dd>
        </dl>

        <p><a href="{{ route('ventas.index') }}">← Volver a ventas</a></p>
    </div>
@endsection
