@extends('layouts.app')

@section('titulo', 'Categorías')

@section('contenido')
    <div class="barra-titulo">
        <h1>Categorías</h1>
        @can('create', \App\Models\Categoria::class)
            <a href="{{ route('categorias.create') }}" class="btn ok">+ Nueva categoría</a>
        @endcan
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Productos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($categorias as $categoria)
            <tr>
                <td><a href="{{ route('categorias.show', $categoria) }}">{{ $categoria->nombre }}</a></td>
                <td>{{ $categoria->descripcion ?: '—' }}</td>
                <td>{{ $categoria->productos_count }}</td>
                <td>
                    <div class="acciones">
                        @can('update', $categoria)
                            <a href="{{ route('categorias.edit', $categoria) }}" class="btn">Editar</a>
                        @endcan
                        @can('delete', $categoria)
                            <form method="POST" action="{{ route('categorias.destroy', $categoria) }}"
                                  onsubmit="return confirm('¿Eliminar esta categoría?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn peligro">Eliminar</button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="meta">No hay categorías registradas.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection
