@extends('layouts.app')

@section('titulo', 'Usuarios')

@section('contenido')
    <div class="barra-titulo">
        <h1>Usuarios</h1>
        @can('create', \App\Models\Usuario::class)
            <a href="{{ route('usuarios.create') }}" class="btn ok">+ Nuevo usuario</a>
        @endcan
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->nombre_completo }}</td>
                <td>{{ $usuario->correo }}</td>
                <td><span class="etiqueta">{{ $usuario->rol }}</span></td>
                <td>
                    <div class="acciones">
                        @can('view', $usuario)
                            <a href="{{ route('usuarios.show', $usuario) }}" class="btn sec">Ver</a>
                        @endcan
                        @can('update', $usuario)
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn">Editar</a>
                        @endcan
                        @can('delete', $usuario)
                            <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}"
                                  onsubmit="return confirm('¿Eliminar este usuario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn peligro">Eliminar</button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
