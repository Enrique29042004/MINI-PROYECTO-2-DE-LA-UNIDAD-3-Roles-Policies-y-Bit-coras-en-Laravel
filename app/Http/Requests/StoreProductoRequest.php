<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MensajesEnEspanol;
use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
{
    use MensajesEnEspanol;

    /** La autorizacion se delega a ProductoPolicy::create. */
    public function authorize(): bool
    {
        return $this->user()->can('create', Producto::class);
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3', 'max:100', 'unique:productos,nombre'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'precio' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'existencia' => ['required', 'integer', 'min:0', 'max:100000'],
            'categorias' => ['nullable', 'array'],
            'categorias.*' => ['integer', 'exists:categorias,id'],
        ];
    }
}
