<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MensajesEnEspanol;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductoRequest extends FormRequest
{
    use MensajesEnEspanol;

    /** La autorizacion se delega a ProductoPolicy::update sobre el producto de la ruta. */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('producto'));
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required', 'string', 'min:3', 'max:100',
                Rule::unique('productos', 'nombre')->ignore($this->route('producto')),
            ],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'precio' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'existencia' => ['required', 'integer', 'min:0', 'max:100000'],
            'categorias' => ['nullable', 'array'],
            'categorias.*' => ['integer', 'exists:categorias,id'],
        ];
    }
}
