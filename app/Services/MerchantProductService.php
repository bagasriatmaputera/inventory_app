<?php

namespace App\Services;

use App\Repository\MerchantProductRepository;
use App\Repository\MerchantRepository;
use App\Repository\WarehouseProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MerchantProductService
{
    private MerchantProductRepository $merchantProductRepository;
    private WarehouseProductRepository $warehouseProductRepository;
    private MerchantRepository $merchantRepository;
    public function __construct(MerchantProductRepository $merchantProductRepository, WarehouseProductRepository $warehouseProductRepository, MerchantRepository $merchantRepository)
    {
        $this->$merchantProductRepository = $merchantProductRepository;
        $this->$warehouseProductRepository = $warehouseProductRepository;
        $this->$merchantRepository = $merchantRepository;
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
    public function updateStock(int $merchantId, int $productId, int $warehouseId, int $stock)
    {
        return DB::transaction(function () use ($merchantId, $productId, $warehouseId, $stock) {
            $exists = $this->merchantProductRepository->getByMerchantProduct($merchantId, $productId);
            if (!$exists) {
                throw ValidationException::withMessages([
                    'product' => 'Product not found in this merchant'
                ]);
            }
            $currentStock = $exists->stock;
            if ($stock > $currentStock) {
                $diff = $stock - $currentStock;
                $warehouse = $this->warehouseProductRepository->getByWarehouseAndProduct($warehouseId, $productId);
                if (!$warehouse || $warehouse->stock < $diff) {
                    throw ValidationException::withMessages([
                        'stock' => 'insufficent stock at Warehouse'
                    ]);
                }
                $this->warehouseProductRepository->updateStock($warehouseId, $productId, $warehouse->stock - $diff);
            }
            if ($stock < $currentStock) {
                $diff = $currentStock - $stock;
                $warehouse = $this->warehouseProductRepository->getByWarehouseAndProduct($warehouseId, $productId);
                if (!$warehouse) {
                    throw ValidationException::withMessages([
                        'product' => 'Product not exist at Warehouse'
                    ]);
                }
                $this->warehouseProductRepository->updateStock($warehouseId, $productId, $warehouse->stock + $diff);
            }
            $this->merchantProductRepository->updateStock($stock, $merchantId, $productId);
        });
    }
    public function removeProductFromMerchant(int $merchantId, int $productId)
    {
        $merchant = $this->merchantRepository->getById($merchantId, $fields ?? ['*']);
        if (!$merchant) {
            throw ValidationException::withMessages([
                'merchant' => 'Merchant not found'
            ]);
        }
        $product = $this->merchantProductRepository->getByMerchantProduct($merchantId, $productId);
        if (!$product) {
            throw ValidationException::withMessages([
                'product' => 'Product not exsist in this merchant'
            ]);
        }
        return $merchant->products()->detach($productId);
    }
}
