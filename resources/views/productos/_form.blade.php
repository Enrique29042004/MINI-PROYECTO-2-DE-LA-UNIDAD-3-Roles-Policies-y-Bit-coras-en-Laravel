@if ($errors->any())
    <div class="alerta error">
        <strong>Corrige los siguientes errores:</strong>
        <ul style="margin:.4rem 0 0 1.1rem; padding:0;">
            @foreach ($errors->all() as $mensaje)
                <li>{{ $mensaje }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $seleccionadas = old('categorias', isset($producto) ? $producto->categorias->pluck('id')->all() : []);
@endphp

<label for="nombre">Nombre</label>
<input type="text" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre ?? '') }}">
@error('nombre') <div class="campo-error">{{ $message }}</div> @enderror

<label for="descripcion">Descripción</label>
<textarea id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
@error('descripcion') <div class="campo-error">{{ $message }}</div> @enderror

<label for="precio">Precio</label>
<input type="number" step="0.01" id="precio" name="precio" value="{{ old('precio', $producto->precio ?? '') }}">
@error('precio') <div class="campo-error">{{ $message }}</div> @enderror

<label for="existencia">Existencia</label>
<input type="number" id="existencia" name="existencia" value="{{ old('existencia', $producto->existencia ?? '') }}">
@error('existencia') <div class="campo-error">{{ $message }}</div> @enderror

<label>Categorías</label>
<div class="checks">
    @foreach ($categorias as $categoria)
        <label>
            <input type="checkbox" name="categorias[]" value="{{ $categoria->id }}"
                   @checked(in_array($categoria->id, $seleccionadas))>
            {{ $categoria->nombre }}
        </label>
    @endforeach
</div>
@error('categorias') <div class="campo-error">{{ $message }}</div> @enderror
@error('categorias.*') <div class="campo-error">{{ $message }}</div> @enderror

<p style="margin-top:1.2rem;">
    <button type="submit" class="btn">Guardar</button>
    <a href="{{ route('productos.index') }}" class="btn sec">Cancelar</a>
</p>
