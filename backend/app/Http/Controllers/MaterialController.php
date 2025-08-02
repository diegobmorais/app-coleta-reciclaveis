<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Http\Requests\StoreMaterialRequest;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::query();
      
        if ($search = $request->input('search')) {
            $query->where('name', 'ILIKE', "%{$search}%")
                ->orWhere('description', 'ILIKE', "%{$search}%")
                ->orWhere('category', 'ILIKE', "%{$search}%");
        }
     
        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc');

        if (in_array($sortBy, ['name', 'category', 'created_at']) && in_array($sortDir, ['asc', 'desc'])) {
            $query->orderBy($sortBy, $sortDir);
        }
    
        $perPage = $request->input('per_page', 10);

        return $query->paginate($perPage);
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
