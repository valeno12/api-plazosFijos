<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlazoFijoController;


Route::apiResource('plazos-fijos', PlazoFijoController::class);