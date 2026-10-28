<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso denegado</title>
    <style>
        body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; background:#f3f5f8; color:#1d2733;
               display:flex; min-height:100vh; align-items:center; justify-content:center; margin:0; }
        .caja { background:#fff; border:1px solid #d9dee6; border-radius:12px; padding:2rem 2.4rem; text-align:center; max-width:460px; }
        h1 { margin:0 0 .4rem; color:#b3261e; }
        a { color:#1f4e8c; }
    </style>
</head>
<body>
    <div class="caja">
        <h1>403 · Acceso denegado</h1>
        <p>No tienes permiso para realizar esta acción.</p>
        <p><a href="{{ url('/') }}">Volver al inicio</a></p>
    </div>
</body>
</html>
