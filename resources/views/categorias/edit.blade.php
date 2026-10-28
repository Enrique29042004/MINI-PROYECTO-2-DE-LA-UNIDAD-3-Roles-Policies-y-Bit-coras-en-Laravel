@extends('layouts.app')

@section('titulo', 'Editar categoría')

@section('contenido')
    <div class="tarjeta">
        <h1>Editar categoría</h1>

        <form method="POST" action="{{ route('categorias.update', $categoria) }}" class="formulario">
            @csrf
            @method('PUT')
            @include('categorias._form')
        </form>
    </div>
@endsection
