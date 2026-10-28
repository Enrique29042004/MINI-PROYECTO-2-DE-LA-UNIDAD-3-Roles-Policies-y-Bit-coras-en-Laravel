<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    // Habilita $this->authorize('accion', $modelo) en todos los controllers.
    use AuthorizesRequests;
}
