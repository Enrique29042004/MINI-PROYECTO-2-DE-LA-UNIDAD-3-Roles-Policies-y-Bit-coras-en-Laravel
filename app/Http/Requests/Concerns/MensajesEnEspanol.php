<?php

namespace App\Http\Requests\Concerns;

/**
 * Mensajes de validacion en espanol para los FormRequest.
 * (Evita depender de archivos de idioma.)
 */
trait MensajesEnEspanol
{
    protected function mensajesBase(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto.',
            'numeric' => 'El campo :attribute debe ser un numero.',
            'integer' => 'El campo :attribute debe ser un numero entero.',
            'date' => 'El campo :attribute debe ser una fecha valida.',
            'email' => 'El campo :attribute debe ser un correo valido.',
            'array' => 'El campo :attribute no tiene un formato valido.',
            'exists' => 'El valor seleccionado en :attribute no es valido.',
            'unique' => 'El valor de :attribute ya esta registrado.',
            'in' => 'El valor seleccionado en :attribute no es valido.',
            'min' => 'El campo :attribute no cumple el valor/longitud minima (:min).',
            'max' => 'El campo :attribute supera el valor/longitud maxima (:max).',
            'before_or_equal' => 'El campo :attribute no puede ser una fecha futura.',
        ];
    }

    public function messages(): array
    {
        return $this->mensajesBase();
    }
}
