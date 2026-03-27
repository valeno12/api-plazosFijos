<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plazo_fijos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_cuenta');
            $table->string('tipo_moneda', 3);
            $table->decimal('monto', 15, 2);
            $table->decimal('tasa_anual', 6, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_vencimiento');
            $table->integer('plazo_dias');
            $table->decimal('interes_calculado', 15, 2);
            $table->decimal('monto_final', 15, 2);
            $table->enum('estado', ['ACTIVO', 'VENCIDO', 'CANCELADO', 'RENOVADO'])->default('ACTIVO');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plazo_fijos');
    }
};
