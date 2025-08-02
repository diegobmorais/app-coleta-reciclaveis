<?php 
namespace App\Http\Controllers;

use App\Models\Material;
use App\Http\Requests\StoreMaterialRequest;

class MaterialController extends Controller
{
    public function index()
    {
        return Material::all();
    }

    public function store(StoreMaterialRequest $request)
    {
        $material = Material::create($request->validated());
        return response()->json($material, 201);
    }

    public function show(Material $material)
    {
        return $material;
    }

    public function update(StoreMaterialRequest $request, Material $material)
    {
        $material->update($request->validated());
        return response()->json($material);
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return response()->json(['message' => 'Material removido com sucesso.']);
    }
}
