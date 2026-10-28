<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MensajesEnEspanol;
use App\Models\Venta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVentaRequest extends FormRequest
{
    use MensajesEnEspanol;

    /** Solo quien puede "comprar" (VentaPolicy::create) puede registrar una venta. */
    public function authorize(): bool
    {
        return $this->user()->can('create', Venta::class);
    }

    public function rules(): array
    {
        return [
            // El producto debe existir y tener existencia disponible.
            'producto_id' => [
                'required',
                'integer',
                Rule::exists('productos', 'id')->where(fn ($consulta) => $consulta->where('existencia', '>', 0)),
            ],
            'fecha' => ['nullable', 'date', 'before_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return array_merge($this->mensajesBase(), [
            'producto_id.exists' => 'El producto no existe o no tiene existencia disponible.',
        ]);
    }
}
