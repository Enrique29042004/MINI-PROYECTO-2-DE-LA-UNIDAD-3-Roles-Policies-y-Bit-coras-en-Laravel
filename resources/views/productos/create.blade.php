@extends('layouts.app')

@section('titulo', 'Nuevo producto')

@section('contenido')
    <div class="tarjeta">
        <h1>Nuevo producto</h1>
        <p class="meta">El vendedor será el usuario con sesión iniciada ({{ Auth::user()->nombre_completo }}).</p>

        <form method="POST" action="{{ route('productos.store') }}" class="formulario">
            @csrf
            @include('productos._form')
        </form>
    </div>
@endsection
