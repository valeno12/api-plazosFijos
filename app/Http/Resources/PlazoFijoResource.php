<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlazoFijoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'numeroCuenta'      => $this->numero_cuenta,
            'tipoMoneda'        => $this->tipo_moneda,
            'monto'             => (float) $this->monto,
            'tasaAnual'         => (float) $this->tasa_anual,
            'fechaInicio'       => $this->fecha_inicio->format('Y-m-d'),
            'fechaVencimiento'  => $this->fecha_vencimiento->format('Y-m-d'),
            'plazoDias'         => $this->plazo_dias,
            'interesCalculado'  => (float) $this->interes_calculado,
            'montoFinal'        => (float) $this->monto_final,
            'estado'            => $this->estado,
            'creadoEn'          => $this->created_at?->toIso8601String(),
            'actualizadoEn'     => $this->updated_at?->toIso8601String(),
        ];
    }
}
