<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Login manual (sin Breeze, Jetstream ni laravel/ui).
 */
class AuthController extends Controller
{
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        // La contrasena viaja en el campo "clave", pero Auth::attempt espera la
        // llave "password"; el correo se usa para buscar al usuario.
        $credenciales = [
            'correo' => $request->validated('correo'),
            'password' => $request->validated('clave'),
        ];

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();

            $usuario = Auth::user();

            Log::channel('autenticacion')->info('Login exitoso', [
                'usuario_id' => $usuario->id,
                'correo' => $usuario->correo,
                'ip' => $request->ip(),
            ]);

            return redirect()->intended(route('home'));
        }

        Log::channel('autenticacion')->warning('Login incorrecto', [
            'correo' => $request->validated('correo'),
            'ip' => $request->ip(),
        ]);

        return back()
            ->withErrors(['correo' => 'Las credenciales no coinciden con nuestros registros.'])
            ->onlyInput('correo');
    }

    public function logout(Request $request): RedirectResponse
    {
        $usuario = Auth::user();

        Log::channel('autenticacion')->info('Logout', [
            'usuario_id' => $usuario?->id,
            'correo' => $usuario?->correo,
            'ip' => $request->ip(),
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
