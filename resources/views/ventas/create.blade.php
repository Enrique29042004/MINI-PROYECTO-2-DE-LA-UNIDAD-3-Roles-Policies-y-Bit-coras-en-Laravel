@extends('layouts.app')

@section('titulo', 'Comprar producto')

@section('contenido')
    <div class="tarjeta">
        <h1>Comprar producto</h1>
        <p class="meta">Cliente: {{ Auth::user()->nombre_completo }}. El vendedor y el total se toman del producto elegido.</p>

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

        <form method="POST" action="{{ route('ventas.store') }}" class="formulario">
            @csrf

            <label for="producto_id">Producto</label>
            <select id="producto_id" name="producto_id">
                <option value="">— Selecciona un producto —</option>
                @foreach ($productos as $producto)
                    <option value="{{ $producto->id }}" @selected((int) old('producto_id', $seleccionado) === $producto->id)>
                        {{ $producto->nombre }} · ${{ number_format($producto->precio, 2) }}
                        · vende {{ $producto->usuario->nombre_completo }} · quedan {{ $producto->existencia }}
                    </option>
                @endforeach
            </select>

            <label for="fecha">Fecha (opcional, por defecto hoy)</label>
            <input type="date" id="fecha" name="fecha" value="{{ old('fecha') }}">

            <p style="margin-top:1.2rem;">
                <button type="submit" class="btn ok">Confirmar compra</button>
                <a href="{{ route('productos.index') }}" class="btn sec">Cancelar</a>
            </p>
        </form>
    </div>
@endsection
