<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MensajesEnEspanol;
use App\Models\Usuario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUsuarioRequest extends FormRequest
{
    use MensajesEnEspanol;

    public function authorize(): bool
    {
        return $this->user()->can('create', Usuario::class);
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:2', 'max:60'],
            'apellidos' => ['required', 'string', 'min:2', 'max:80'],
            'correo' => ['required', 'string', 'email', 'max:120', 'unique:usuarios,correo'],
            'clave' => ['required', 'string', 'min:3', 'max:255'],
            'rol' => ['required', Rule::in(Usuario::ROLES)],
        ];
    }
}
