@extends('layouts.app')

@section('titulo', 'Iniciar sesión')

@section('contenido')
    <div class="tarjeta" style="max-width:420px; margin:3rem auto;">
        <h1>Iniciar sesión</h1>

        @if ($errors->any())
            <div class="alerta error">
                @foreach ($errors->all() as $mensaje)
                    <div>{{ $mensaje }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.intentar') }}" class="formulario">
            @csrf

            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" value="{{ old('correo') }}" autofocus>

            <label for="clave">Contraseña</label>
            <input type="password" id="clave" name="clave">

            <p><button type="submit" class="btn">Entrar</button></p>
        </form>
    </div>
@endsection
