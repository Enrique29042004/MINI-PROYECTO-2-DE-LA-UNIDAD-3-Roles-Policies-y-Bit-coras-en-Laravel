@extends('layouts.app')

@section('titulo', 'Editar venta #'.$venta->id)

@section('contenido')
    <div class="tarjeta">
        <h1>Editar venta #{{ $venta->id }}</h1>
        <p class="meta">
            {{ $venta->producto->nombre }} · Cliente: {{ $venta->cliente->nombre_completo }}
            · Vendedor: {{ $venta->vendedor->nombre_completo }}
        </p>

        @if ($errors->any())
            <div class="alerta error">
                <strong>Corrige los siguientes errores:</strong>
                <ul style="margin:.4rem 0 0 1.1rem; padding:0;">
                    @foreach ($errors->all() as $mensaje)
                        <li>{{ $mensaje }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('ventas.update', $venta) }}" class="formulario">
            @csrf
            @method('PUT')

            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="{{ old('fecha', $venta->fecha->format('Y-m-d')) }}">

            <label for="total">Total</label>
            <input type="number" step="0.01" id="total" name="total" value="{{ old('total', $venta->total) }}">

            <p style="margin-top:1.2rem;">
                <button type="submit" class="btn">Guardar</button>
                <a href="{{ route('ventas.index') }}" class="btn sec">Cancelar</a>
            </p>
        </form>
    </div>
@endsection
