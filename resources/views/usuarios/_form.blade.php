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
<input type="text" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre ?? '') }}">
@error('nombre') <div class="campo-error">{{ $message }}</div> @enderror

<label for="apellidos">Apellidos</label>
<input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos', $usuario->apellidos ?? '') }}">
@error('apellidos') <div class="campo-error">{{ $message }}</div> @enderror

<label for="correo">Correo</label>
<input type="email" id="correo" name="correo" value="{{ old('correo', $usuario->correo ?? '') }}">
@error('correo') <div class="campo-error">{{ $message }}</div> @enderror

<label for="clave">Contraseña @isset($usuario) <span class="meta">(déjala vacía para conservar la actual)</span> @endisset</label>
<input type="password" id="clave" name="clave">
@error('clave') <div class="campo-error">{{ $message }}</div> @enderror

{{-- El rol solo lo puede asignar quien pase el Gate "asignar-rol" (administrador). --}}
@can('asignar-rol')
    <label for="rol">Rol</label>
    <select id="rol" name="rol">
        @foreach (\App\Models\Usuario::ROLES as $rol)
            <option value="{{ $rol }}" @selected(old('rol', $usuario->rol ?? 'cliente') === $rol)>{{ ucfirst($rol) }}</option>
        @endforeach
    </select>
    @error('rol') <div class="campo-error">{{ $message }}</div> @enderror
@else
    <p class="meta">Rol actual: <strong>{{ $usuario->rol ?? 'cliente' }}</strong> (solo un administrador puede cambiarlo).</p>
@endcan

<p style="margin-top:1.2rem;">
    <button type="submit" class="btn">Guardar</button>
    <a href="{{ route('usuarios.index') }}" class="btn sec">Cancelar</a>
</p>
