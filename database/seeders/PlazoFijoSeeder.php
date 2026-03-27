<?php

namespace Database\Seeders;

use App\Models\PlazoFijo;
use Illuminate\Database\Seeder;

class PlazoFijoSeeder extends Seeder
{
    public function run(): void
    {
        $plazos = [
            [
                'numero_cuenta'     => '386-00123-45678',
                'tipo_moneda'       => 'ARS',
                'monto'             => 100000.00,
                'tasa_anual'        => 85.50,
                'fecha_inicio'      => '2024-01-15',
                'fecha_vencimiento' => '2024-04-15',
                'plazo_dias'        => 90,
                'estado'            => 'ACTIVO',
            ],
            [
                'numero_cuenta'     => '386-00456-78901',
                'tipo_moneda'       => 'USD',
                'monto'             => 5000.00,
                'tasa_anual'        => 4.50,
                'fecha_inicio'      => '2024-02-01',
                'fecha_vencimiento' => '2024-08-01',
                'plazo_dias'        => 182,
                'estado'            => 'ACTIVO',
            ],
            [
                'numero_cuenta'     => '386-00789-12345',
                'tipo_moneda'       => 'ARS',
                'monto'             => 250000.00,
                'tasa_anual'        => 75.00,
                'fecha_inicio'      => '2023-06-01',
                'fecha_vencimiento' => '2023-09-01',
                'plazo_dias'        => 92,
                'estado'            => 'VENCIDO',
            ],
            [
                'numero_cuenta'     => '386-00321-65432',
                'tipo_moneda'       => 'ARS',
                'monto'             => 50000.00,
                'tasa_anual'        => 90.00,
                'fecha_inicio'      => '2024-03-01',
                'fecha_vencimiento' => '2024-04-01',
                'plazo_dias'        => 31,
                'estado'            => 'CANCELADO',
            ],
            [
                'numero_cuenta'     => '386-00654-98765',
                'tipo_moneda'       => 'ARS',
                'monto'             => 500000.00,
                'tasa_anual'        => 80.00,
                'fecha_inicio'      => '2023-10-01',
                'fecha_vencimiento' => '2024-01-01',
                'plazo_dias'        => 92,
                'estado'            => 'RENOVADO',
            ],
        ];

        foreach ($plazos as $data) {
            $plazo = new PlazoFijo($data);
            $plazo->calcularInteres();
            $plazo->save();
        }
    }
}
