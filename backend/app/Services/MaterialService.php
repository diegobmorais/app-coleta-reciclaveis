<?php

namespace App\Services;

use App\Repositories\MaterialRepository;
use App\Models\Material;

class MaterialService
{
    protected $repository;

    public function __construct(MaterialRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getMaterials($filters)
    {
        $query = $this->repository->query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('description', 'ILIKE', "%{$search}%")
                    ->orWhere('category', 'ILIKE', "%{$search}%");
            });
        }

        $sortBy = $filters['sort_by'] ?? 'name';
        $sortDir = $filters['sort_dir'] ?? 'asc';

        if (in_array($sortBy, ['name', 'category', 'created_at']) && in_array($sortDir, ['asc', 'desc'])) {
            $query->orderBy($sortBy, $sortDir);
        }

        $perPage = $filters['per_page'] ?? 10;

        return $this->repository->paginate($query, $perPage);
    }

    public function createMaterial(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateMaterial(Material $material, array $data)
    {
        return $this->repository->update($material, $data);
    }

    public function deleteMaterial(Material $material)
    {
        return $this->repository->delete($material);
    }
}
