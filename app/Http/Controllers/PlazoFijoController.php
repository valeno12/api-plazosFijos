<?php

namespace App\Http\Controllers;

use App\Models\PlazoFijo;
use Illuminate\Http\Request;
use App\Http\Requests\PlazoFijoRequest;
use App\Http\Resources\PlazoFijoResource;

class PlazoFijoController extends Controller
{
    public function index(Request $request)
    {
        $plazos = PlazoFijo::query()
            ->when($request->filled('estado'), fn ($query) => $query->where('estado', $request->estado))
            ->when($request->filled('tipo_moneda'), fn ($query) => $query->where('tipo_moneda', $request->tipo_moneda))
            ->when($request->filled('numero_cuenta'), fn ($query) => $query->where('numero_cuenta', 'like', '%' . $request->numero_cuenta . '%'))
            ->paginate(10)
            ->withQueryString();

        return PlazoFijoResource::collection($plazos);
    }

    public function store(PlazoFijoRequest $request)
    {
        $plazoFijo = new PlazoFijo($request->validated());
        $plazoFijo->calcularInteres();
        $plazoFijo->save();
        $plazoFijo->refresh();

        return (new PlazoFijoResource($plazoFijo))
            ->additional(['message' => 'Plazo fijo creado exitosamente.'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(PlazoFijo $plazos_fijo)
    {
        return new PlazoFijoResource($plazos_fijo);
    }

    public function update(PlazoFijoRequest $request, PlazoFijo $plazos_fijo)
    {
        $plazos_fijo->fill($request->validated());
        $plazos_fijo->calcularInteres();
        $plazos_fijo->save();

        return new PlazoFijoResource($plazos_fijo);
    }

    public function destroy(PlazoFijo $plazos_fijo)
    {
        $plazos_fijo->delete();

        return response()->json(['message' => 'Plazo fijo eliminado exitosamente.']);
    }
}


