@extends('layouts.app')

@section('titulo', 'Nuevo usuario')

@section('contenido')
    <div class="tarjeta">
        <h1>Nuevo usuario</h1>

        <form method="POST" action="{{ route('usuarios.store') }}" class="formulario">
            @csrf
            @include('usuarios._form')
        </form>
    </div>
@endsection
