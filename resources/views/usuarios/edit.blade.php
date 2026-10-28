@extends('layouts.app')

@section('titulo', 'Editar usuario')

@section('contenido')
    <div class="tarjeta">
        <h1>Editar usuario</h1>

        <form method="POST" action="{{ route('usuarios.update', $usuario) }}" class="formulario">
            @csrf
            @method('PUT')
            @include('usuarios._form')
        </form>
    </div>
@endsection
