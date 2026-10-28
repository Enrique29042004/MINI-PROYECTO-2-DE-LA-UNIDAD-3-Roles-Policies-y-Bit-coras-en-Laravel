@extends('layouts.app')

@section('titulo', 'Nueva categoría')

@section('contenido')
    <div class="tarjeta">
        <h1>Nueva categoría</h1>

        <form method="POST" action="{{ route('categorias.store') }}" class="formulario">
            @csrf
            @include('categorias._form')
        </form>
    </div>
@endsection
