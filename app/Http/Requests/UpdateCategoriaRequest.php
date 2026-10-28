<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MensajesEnEspanol;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoriaRequest extends FormRequest
{
    use MensajesEnEspanol;

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('categoria'));
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required', 'string', 'min:3', 'max:80',
                Rule::unique('categorias', 'nombre')->ignore($this->route('categoria')),
            ],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ];
    }
}
