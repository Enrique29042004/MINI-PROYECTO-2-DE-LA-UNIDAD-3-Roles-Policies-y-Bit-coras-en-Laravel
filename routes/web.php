<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas publicas (solo invitados): login manual
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login.intentar');
});

/*
|--------------------------------------------------------------------------
| Rutas protegidas (requieren sesion). El acceso por rol lo deciden las
| Policies dentro de cada controller / FormRequest.
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::view('/', 'home')->name('home');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('productos', ProductoController::class)
        ->parameters(['productos' => 'producto']);

    Route::resource('categorias', CategoriaController::class)
        ->parameters(['categorias' => 'categoria']);

    Route::resource('ventas', VentaController::class)
        ->parameters(['ventas' => 'venta']);

    Route::resource('usuarios', UsuarioController::class)
        ->parameters(['usuarios' => 'usuario']);
});
