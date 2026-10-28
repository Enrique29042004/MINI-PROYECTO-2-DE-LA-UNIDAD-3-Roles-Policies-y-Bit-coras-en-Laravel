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

<label for="nombre">Nombre</label>
<input type="text" id="nombre" name="nombre" value="{{ old('nombre', $categoria->nombre ?? '') }}">
@error('nombre') <div class="campo-error">{{ $message }}</div> @enderror

<label for="descripcion">Descripción</label>
<textarea id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $categoria->descripcion ?? '') }}</textarea>
@error('descripcion') <div class="campo-error">{{ $message }}</div> @enderror

<p style="margin-top:1.2rem;">
    <button type="submit" class="btn">Guardar</button>
    <a href="{{ route('categorias.index') }}" class="btn sec">Cancelar</a>
</p>
