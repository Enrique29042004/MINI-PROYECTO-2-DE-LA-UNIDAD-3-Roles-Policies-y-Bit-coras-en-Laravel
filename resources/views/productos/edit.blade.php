@extends('layouts.app')

@section('titulo', 'Editar producto')

@section('contenido')
    <div class="tarjeta">
        <h1>Editar producto</h1>
        <p class="meta">Vendedor: {{ $producto->usuario->nombre_completo }}</p>

        <form method="POST" action="{{ route('productos.update', $producto) }}" class="formulario">
            @csrf
            @method('PUT')
            @include('productos._form')
        </form>
    </div>
@endsection
