@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
    <div class="tarjeta">
        <h1>Bienvenido, {{ Auth::user()->nombre }}</h1>
        <p class="meta">Has iniciado sesión como <strong>{{ Auth::user()->rol }}</strong> ({{ Auth::user()->correo }}).</p>

        <h2>¿Qué puedes hacer?</h2>
        <ul>
            <li><a href="{{ route('productos.index') }}">Ver productos</a>
                @can('comprar-productos') y comprarlos @endcan</li>
            @can('create', \App\Models\Producto::class)
                <li><a href="{{ route('productos.create') }}">Registrar un producto</a></li>
            @endcan
            @can('create', \App\Models\Categoria::class)
                <li><a href="{{ route('categorias.create') }}">Registrar una categoría</a></li>
            @endcan
            <li><a href="{{ route('ventas.index') }}">Consultar ventas</a></li>
            @can('viewAny', \App\Models\Usuario::class)
                <li><a href="{{ route('usuarios.index') }}">Administrar usuarios</a></li>
            @endcan
        </ul>
    </div>
@endsection
