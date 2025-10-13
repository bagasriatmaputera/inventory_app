<?php

namespace App\Repository;

use App\Models\Product;

class ProductRepository
{
    public function getAll(array $fields)
    {
        return Product::select($fields)->with('category')->latest()->paginate(10);
    }
    public function getById(int $id, array $fields)
    {
        return Product::select($fields)->with('category')->findOrFail($id);
    }
    public function create(array $data)
    {
        return Product::create($data);
    }
    public function update(int $id, array $data)
    {
        $products = $this->getById($id, $fields ?? ['*']);
        return $products->update($data);
    }
    public function delete(int $id)
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }
}
