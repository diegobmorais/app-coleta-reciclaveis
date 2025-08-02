<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Services\MaterialService;
use App\Http\Requests\StoreMaterialRequest;

class MaterialController extends Controller
{
    protected $service;

    public function __construct(MaterialService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort_by', 'sort_dir', 'per_page']);
        $materials = $this->service->getMaterials($filters);

        return response()->json($materials,200);
    }

    public function store(StoreMaterialRequest $request)
    {
        $material = $this->service->createMaterial($request->validated());
        return response()->json($material, 201);
    }

    public function show(Material $material)
    {
        return response()->json($material, 200);
    }

    public function update(StoreMaterialRequest $request, Material $material)
    {
        $material = $this->service->updateMaterial($material, $request->validated());
        return response()->json($material, 200);
    }

    public function destroy(Material $material)
    {
        $this->service->deleteMaterial($material);
        return response()->json(['message' => 'Material removido com sucesso.'], 200);
    }
}
