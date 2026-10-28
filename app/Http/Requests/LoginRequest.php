<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MensajesEnEspanol;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use MensajesEnEspanol;

    public function authorize(): bool
    {
        return true; // formulario publico
    }

    public function rules(): array
    {
        return [
            'correo' => ['required', 'string', 'email', 'max:120'],
            'clave' => ['required', 'string', 'max:255'],
        ];
    }
}
