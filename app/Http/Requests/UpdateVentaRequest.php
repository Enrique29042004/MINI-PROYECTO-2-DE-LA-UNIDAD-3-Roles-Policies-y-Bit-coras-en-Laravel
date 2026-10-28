<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MensajesEnEspanol;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVentaRequest extends FormRequest
{
    use MensajesEnEspanol;

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('venta'));
    }

    public function rules(): array
    {
        return [
            'fecha' => ['required', 'date', 'before_or_equal:today'],
            'total' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }
}
