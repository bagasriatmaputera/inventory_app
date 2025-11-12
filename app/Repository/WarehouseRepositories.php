<?php
namespace App\Repository;

use App\Models\Category;
use App\Models\Warehouse;

class WarehouseRepositories {
    public function getAll(array $fields){
        return Warehouse::with(['products.category'])->latest()->paginate(10);
    }
    public function getById(int $id, array $fields){
        return Warehouse::with(['products.category'])->findOrFail($id);
    }
    public function create(array $data){
        return Warehouse::create($data);
    }
    public function update(int $id, array $data){
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update($data);
        return $warehouse;
    }
    public function delete(int $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();
        return $warehouse;
    }
}
