<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MensajesEnEspanol;
use App\Models\Usuario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
{
    use MensajesEnEspanol;

    /** UsuarioPolicy::update: el gerente solo puede editar clientes. */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('usuario'));
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:2', 'max:60'],
            'apellidos' => ['required', 'string', 'min:2', 'max:80'],
            'correo' => [
                'required', 'string', 'email', 'max:120',
                Rule::unique('usuarios', 'correo')->ignore($this->route('usuario')),
            ],
            // Opcional: si se deja vacia se conserva la clave actual.
            'clave' => ['nullable', 'string', 'min:3', 'max:255'],
            // Solo se aplica si quien edita pasa el Gate "asignar-rol" (ver controller).
            'rol' => ['sometimes', Rule::in(Usuario::ROLES)],
        ];
    }
}
