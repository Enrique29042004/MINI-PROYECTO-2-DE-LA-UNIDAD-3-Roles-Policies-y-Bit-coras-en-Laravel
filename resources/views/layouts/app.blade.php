<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Inicio') · Mini E-commerce</title>
    <style>
        :root { --azul:#1f4e8c; --gris:#f3f5f8; --borde:#d9dee6; --texto:#1d2733; --rojo:#b3261e; --verde:#1e7a46; }
        * { box-sizing: border-box; }
        body { margin:0; font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; color:var(--texto); background:var(--gris); }
        a { color:var(--azul); }
        .barra { background:var(--azul); color:#fff; display:flex; flex-wrap:wrap; align-items:center; gap:1rem; padding:.7rem 1.2rem; }
        .barra a { color:#fff; text-decoration:none; }
        .barra .marca { font-weight:700; margin-right:.6rem; }
        .barra nav { display:flex; gap:1rem; flex:1; }
        .barra nav a:hover { text-decoration:underline; }
        .sesion { display:flex; align-items:center; gap:.6rem; font-size:.9rem; }
        .sesion form { margin:0; }
        .contenedor { max-width:1050px; margin:1.5rem auto; padding:0 1rem; }
        .tarjeta { background:#fff; border:1px solid var(--borde); border-radius:10px; padding:1.2rem 1.4rem; margin-bottom:1rem; }
        h1 { margin:.1rem 0 1rem; font-size:1.5rem; }
        h2 { font-size:1.1rem; margin:1.2rem 0 .5rem; }
        table { width:100%; border-collapse:collapse; background:#fff; }
        th, td { text-align:left; padding:.55rem .6rem; border-bottom:1px solid var(--borde); vertical-align:top; font-size:.93rem; }
        th { background:#eaeff6; }
        .acciones { display:flex; gap:.4rem; flex-wrap:wrap; align-items:center; }
        .acciones form { margin:0; }
        .btn { display:inline-block; border:0; border-radius:6px; padding:.4rem .8rem; font-size:.88rem; cursor:pointer; background:var(--azul); color:#fff; text-decoration:none; }
        .btn.sec { background:#6b7a90; }
        .btn.peligro { background:var(--rojo); }
        .btn.ok { background:var(--verde); }
        .etiqueta { display:inline-block; background:#e3ebf7; color:#183b6b; border-radius:999px; padding:.1rem .6rem; font-size:.78rem; margin:0 .2rem .2rem 0; }
        .rol { background:rgba(255,255,255,.2); border-radius:999px; padding:.1rem .6rem; font-size:.78rem; text-transform:capitalize; }
        .alerta { padding:.7rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:.93rem; }
        .alerta.exito { background:#e3f4ea; border:1px solid #b7e0c6; color:#14532d; }
        .alerta.error { background:#fdecea; border:1px solid #f5c2be; color:#7a1812; }
        form.formulario label { display:block; font-weight:600; margin:.8rem 0 .25rem; }
        form.formulario input[type=text], form.formulario input[type=email], form.formulario input[type=password],
        form.formulario input[type=number], form.formulario input[type=date], form.formulario select, form.formulario textarea {
            width:100%; padding:.5rem .6rem; border:1px solid var(--borde); border-radius:6px; font-size:.95rem; background:#fff; }
        .campo-error { color:var(--rojo); font-size:.85rem; margin-top:.2rem; }
        .checks { display:flex; flex-wrap:wrap; gap:.4rem 1.2rem; }
        .checks label { font-weight:400; margin:0; display:flex; align-items:center; gap:.3rem; }
        .meta { color:#5b6878; font-size:.88rem; }
        .barra-titulo { display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; }
        dl.datos { display:grid; grid-template-columns:max-content 1fr; gap:.4rem 1.2rem; margin:0; }
        dl.datos dt { font-weight:600; }
        dl.datos dd { margin:0; }
    </style>
</head>
<body>
@auth
    <header class="barra">
        <a class="marca" href="{{ route('home') }}">Mini E-commerce</a>
        <nav>
            <a href="{{ route('productos.index') }}">Productos</a>
            <a href="{{ route('categorias.index') }}">Categorías</a>
            <a href="{{ route('ventas.index') }}">Ventas</a>
            @can('viewAny', \App\Models\Usuario::class)
                <a href="{{ route('usuarios.index') }}">Usuarios</a>
            @endcan
        </nav>
        <div class="sesion">
            <span>{{ Auth::user()->nombre_completo }}</span>
            <span class="rol">{{ Auth::user()->rol }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn sec">Salir</button>
            </form>
        </div>
    </header>
@endauth

<main class="contenedor">
    @if (session('exito'))
        <div class="alerta exito">{{ session('exito') }}</div>
    @endif
    @if (session('error'))
        <div class="alerta error">{{ session('error') }}</div>
    @endif

    @yield('contenido')
</main>
</body>
</html>
