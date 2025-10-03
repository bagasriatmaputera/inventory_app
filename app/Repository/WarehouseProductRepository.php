<?php

namespace App\Repository;

use App\Models\WarehouseProduct;
use Illuminate\Validation\ValidationException;

class WarehouseProductRepository
{
    public function getByWarehouseAndProduct(int $warehouseId, int $productId)
    {
        return WarehouseProduct::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->first();
    }
    public function updateAttach(int $warehouseId, int $productId, int $stock)
    {
        $warehouseProduct = $this->getByWarehouseAndProduct($warehouseId, $productId);
        if (!$warehouseProduct) {
            throw ValidationException::withMessages([
                'product' => ['Product not found for this warehouse']
            ]);
        }

        $warehouseProduct->update([
            'stock' => $stock
        ]);

        return $warehouseProduct;
    }
}
