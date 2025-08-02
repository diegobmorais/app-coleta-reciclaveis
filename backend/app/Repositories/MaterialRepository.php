<?php

namespace App\Repositories;

use App\Models\Material;

class MaterialRepository
{
    public function query()
    {
        return Material::query();
    }

    public function paginate($query, $perPage)
    {
        return $query->paginate($perPage);
    }

    public function create(array $data)
    {
        return Material::create($data);
    }

    public function update(Material $material, array $data)
    {
        $material->update($data);
        return $material;
    }

    public function delete(Material $material)
    {
        return $material->delete();
    }
}
