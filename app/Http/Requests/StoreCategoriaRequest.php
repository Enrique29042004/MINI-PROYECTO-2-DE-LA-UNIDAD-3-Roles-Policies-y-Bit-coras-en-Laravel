<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MensajesEnEspanol;
use App\Models\Categoria;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoriaRequest extends FormRequest
{
    use MensajesEnEspanol;

    public function authorize(): bool
    {
        return $this->user()->can('create', Categoria::class);
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3', 'max:80', 'unique:categorias,nombre'],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ];
    }
}
