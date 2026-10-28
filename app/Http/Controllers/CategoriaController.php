<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;

class CategoriaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Categoria::class);

        $categorias = Categoria::withCount('productos')->orderBy('nombre')->get();

        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        $this->authorize('create', Categoria::class);

        return view('categorias.create');
    }

    public function store(StoreCategoriaRequest $request): RedirectResponse
    {
        Categoria::create($request->validated());

        return redirect()->route('categorias.index')->with('exito', 'Categoria creada correctamente.');
    }

    public function show(Categoria $categoria)
    {
        $this->authorize('view', $categoria);

        $categoria->load('productos.usuario');

        return view('categorias.show', compact('categoria'));
    }

    public function edit(Categoria $categoria)
    {
        $this->authorize('update', $categoria);

        return view('categorias.edit', compact('categoria'));
    }

    public function update(UpdateCategoriaRequest $request, Categoria $categoria): RedirectResponse
    {
        $categoria->update($request->validated());

        return redirect()->route('categorias.index')->with('exito', 'Categoria actualizada correctamente.');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        $this->authorize('delete', $categoria);

        // La tabla pivote se limpia sola (cascadeOnDelete); los productos se conservan.
        $categoria->delete();

        return redirect()->route('categorias.index')->with('exito', 'Categoria eliminada correctamente.');
    }
}
