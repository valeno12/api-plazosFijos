<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PlazoFijoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            'numero_cuenta'    => 'required|string|max:50',
            'tipo_moneda'      => 'required|string|size:3',
            'monto'            => 'required|numeric|gt:0',
            'tasa_anual'       => 'required|numeric|between:1,200',
            'fecha_inicio'     => 'required|date',
            'fecha_vencimiento'=> 'required|date|after:fecha_inicio',
            'plazo_dias'       => 'required|integer|between:1,365',
            'estado'           => 'sometimes|in:ACTIVO,VENCIDO,CANCELADO,RENOVADO',
        ];
    }

    public function messages(): array
    {
        return [
            'monto.gt'                    => 'El monto debe ser mayor a 0.',
            'tasa_anual.between'          => 'La tasa anual debe estar entre 1% y 200%.',
            'fecha_vencimiento.after'     => 'La fecha de vencimiento debe ser posterior a la fecha de inicio.',
            'plazo_dias.between'          => 'El plazo en días no puede exceder 365 días.',
            'estado.in'                   => 'El estado debe ser ACTIVO, VENCIDO, CANCELADO o RENOVADO.',
        ];
    }
}
