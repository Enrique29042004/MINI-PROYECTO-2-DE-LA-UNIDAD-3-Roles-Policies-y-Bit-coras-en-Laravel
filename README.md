# Mini E-commerce · Roles, Policies y Bitácoras en Laravel

**Mini Proyecto 2 · Unidad 3.** Sistema E-commerce con relaciones Eloquent, control de acceso por roles mediante **Policies y Gates**, validación con **FormRequest**, **Seeders/Factory** y **bitácoras** con `Log::channel()`. El login es manual (sin Breeze, Jetstream ni laravel/ui).

## Requisitos

- PHP 8.2 o superior (con extensiones `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`)
- Composer
- MySQL / MariaDB (XAMPP, Laragon, etc.) para ver las tablas en phpMyAdmin

## Instalación

```bash
git clone <URL-DE-TU-REPOSITORIO> ecommerce-laravel
cd ecommerce-laravel

composer install
cp .env.example .env          # en Windows: copy .env.example .env
php artisan key:generate
```

1. Crea en phpMyAdmin una base de datos vacía llamada `ecommerce_laravel` (cotejamiento `utf8mb4_unicode_ci`).
2. Revisa `DB_USERNAME` y `DB_PASSWORD` en `.env`.
3. Crea las tablas y los datos de prueba:

```bash
php artisan migrate:fresh --seed
php artisan serve
```

Abre <http://localhost:8000>.

## Usuarios de prueba

**Contraseña de todos: `123`**

| Correo                          | Rol           |
|---------------------------------|---------------|
| `jlopez@tuxtla.tecnm.mx`        | Administrador |
| `msanchez@tuxtla.tecnm.mx`      | Gerente       |
| `phernandez@tuxtla.tecnm.mx`    | Gerente       |
| `mmartinez@tuxtla.tecnm.mx`     | Cliente       |
| `jhernandez@tuxtla.tecnm.mx`    | Cliente       |
| `plopez@tuxtla.tecnm.mx`        | Cliente       |
| `mlopez@tuxtla.tecnm.mx`        | Cliente       |
| `jmartinez@tuxtla.tecnm.mx`     | Cliente       |

> "Mario" y "Maria" comparten inicial, así que `Mario Martinez` y `Maria Martinez` darían el mismo correo (`mmartinez@…`). Como el correo es único, el seeder omite las combinaciones repetidas.

## Reglas de acceso (Policies)

| Acción                       | Administrador | Gerente                    | Cliente |
|------------------------------|:---:|:---:|:---:|
| Crear / eliminar usuarios    | ✔ | ✘ | ✘ |
| Editar usuarios              | ✔ | Solo **clientes** | ✘ |
| Asignar roles (Gate)         | ✔ | ✘ | ✘ |
| Ver productos                | ✔ | ✔ | ✔ |
| Crear productos              | ✔ | ✔ | ✘ |
| Editar / eliminar productos  | Todos | Solo los suyos | ✘ |
| Categorías (crear/editar)    | ✔ | ✔ | ✘ |
| Comprar (crear venta)        | ✘ | ✘ | ✔ |
| Ver ventas                   | Todas | Todas | Solo las suyas |
| Editar / eliminar ventas     | ✔ | ✘ | ✘ |

Los controllers no tienen `if` por rol: usan `$this->authorize()`, `Gate::allows()` y los FormRequest llaman a las Policies desde `authorize()`.

## Estructura relevante

```
app/
  Models/            Usuario (Authenticatable), Producto, Categoria, Venta, CategoriaProducto (pivote)
  Policies/          UsuarioPolicy, ProductoPolicy, CategoriaPolicy, VentaPolicy
  Providers/         AppServiceProvider (registro de Policies y Gates)
  Http/Controllers/  AuthController (login manual), Producto/Categoria/Venta/Usuario
  Http/Requests/     Store/Update de Producto, Categoria, Venta, Usuario + LoginRequest
config/
  auth.php           provider eloquent -> App\Models\Usuario
  logging.php        canales: autenticacion, productos, ventas
database/
  migrations/        usuarios, categorias, productos, categoria_producto, ventas
  factories/         UsuarioFactory
  seeders/           Usuario, Categoria, Producto, Venta
routes/web.php
resources/views/     Blade (login, productos, categorías, ventas, usuarios, 403)
```

## Relaciones Eloquent

- `Usuario 1—N Producto` (`usuario_id` = vendedor) · `hasMany` / `belongsTo`
- `Producto N—N Categoria` (pivote `categoria_producto`) · `belongsToMany` en ambos modelos
- `Producto 1—N Venta` · `Venta` pertenece a `producto`, `cliente` (`cliente_id`) y `vendedor` (`vendedor_id`)
- `Usuario` → `ventasComoCliente()` / `ventasComoVendedor()` · `hasMany`
- `Usuario` → Productos → Categorías: `categoriasPorProductos()` es un `hasManyThrough` (hasta el pivote) y `categorias()` devuelve las categorías sin repetir.

## Bitácoras

Se escriben en `storage/logs/` (no se suben a Git):

| Archivo              | Eventos |
|----------------------|---------|
| `autenticacion.log`  | Login exitoso, login incorrecto, logout |
| `productos.log`      | Producto creado, editado, eliminado |
| `ventas.log`         | Venta creada (también editada / eliminada) |

Ejemplo: `Log::channel('autenticacion')->info('Login exitoso', ['usuario_id' => ..., 'correo' => ..., 'ip' => ...]);`

## Guía rápida para las capturas del reporte

| # | Qué mostrar |
|---|-------------|
| 1 | phpMyAdmin → tabla `usuarios`; `app/Models/Usuario.php`; `config/auth.php` |
| 2 | Login correcto e incorrecto; `AuthController@login` (`Auth::attempt`) |
| 3 | Entrar como **mmartinez** (cliente) y abrir `/productos/1/edit` → **403 Acceso denegado**; `ProductoPolicy` |
| 4 | `/productos` (columna Vendedor); `belongsTo` / `hasMany` |
| 5 | `/productos/1` (varias categorías) y `/categorias/1` (sus productos) |
| 6 | `/ventas` o `/ventas/1` (producto, cliente, vendedor); `Venta.php` |
| 7 | Enviar el formulario de producto vacío → errores; `StoreProductoRequest` |
| 8 | phpMyAdmin con datos sembrados; `UsuarioFactory` y seeders |
| 9 | Contenido de `autenticacion.log`, `productos.log`, `ventas.log`; `config/logging.php` |
| 10 | phpMyAdmin → estructura y diseñador de las tablas (llaves foráneas); migraciones |

## Subir a GitHub

```bash
git init
git add .
git commit -m "Mini Proyecto 2: roles, policies y bitacoras en Laravel"
git branch -M main
git remote add origin https://github.com/<TU-USUARIO>/<TU-REPO>.git
git push -u origin main
```

`.env` y `vendor/` ya están en `.gitignore`, así que no se suben.
