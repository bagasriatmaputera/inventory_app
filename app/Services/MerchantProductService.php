<?php

namespace App\Services;

use App\Repository\MerchantProductRepository;
use App\Repository\WarehouseProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MerchantProductService
{
    private MerchantProductRepository $merchantProductRepository;
    private WarehouseProductRepository $warehouseProductRepository;
    public function __construct(MerchantProductRepository $merchantProductRepository, WarehouseProductRepository $warehouseProductRepository)
    {
        $this->$merchantProductRepository = $merchantProductRepository;
        $this->$warehouseProductRepository = $warehouseProductRepository;
    }
    public function assignProductToMerchant(array $data)
    {
        return DB::transaction(function () use ($data) {
            $warehouseProducts = $this->warehouseProductRepository->getByWarehouseAndProduct($data['warehouse_id'], $data['product_id']);
            if (!$warehouseProducts || $warehouseProducts['stock'] < $data['stock']) {
                throw ValidationException::withMessages([
                    'stock' => 'Insufficient stock in warehouse'
                ]);
            }
            $existingProduct = $this->merchantProductRepository->getByMerchantProduct($data['merchant_id'], $data['product_id']);
            if ($existingProduct) {
                throw ValidationException::withMessages([
                    'product' => 'Product has already exists in tihs merhcant'
                ]);
            }
            // kurangin stock warehouse
            $this->warehouseProductRepository->updateStock(
                $data['warehouse_id'],
                $data['product_id'],
                $warehouseProducts['stock'] - $data['stock']
            );
            return $this->merchantProductRepository->create([
                'warehouse_id' => $data['warehouse_id'],
                'merchant_id' => $data['merchant_id'],
                'product_id' => $data['product_id'],
                'stock' => $data['stock']
            ]);
        });
    }
}
