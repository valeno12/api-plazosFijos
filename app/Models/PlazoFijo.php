<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlazoFijo extends Model
{
    use SoftDeletes;
    protected $table = 'plazo_fijos';
    protected $fillable = [
        'numero_cuenta',
        'tipo_moneda',
        'monto',
        'tasa_anual',
        'fecha_inicio',
        'fecha_vencimiento',
        'plazo_dias',
        'interes_calculado',
        'monto_final',
        'estado',
    ];
    protected $casts = [
        'monto' => 'decimal:2',
        'tasa_anual' => 'decimal:2',
        'interes_calculado' => 'decimal:2',
        'monto_final' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_vencimiento' => 'date',
    ];

    /**
     * Calcula el interés y monto final.
     * Fórmula: Interés = Monto × (TasaAnual / 100 / 365) × Días
     */
    public function calcularInteres(): void
    {
        $this->interes_calculado = round($this->monto * ($this->tasa_anual / 100 / 365) * $this->plazo_dias, 2);
        $this->monto_final = round($this->monto + $this->interes_calculado, 2);
    }

}
