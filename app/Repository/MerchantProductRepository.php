<?php

namespace App\Repository;

use App\Models\MerchantProduct;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Expr\Throw_;

class MerchantProductRepository
{
    public function create(array $data)
    {
        return MerchantProduct::create($data);
    }
    public function getByMerchantProduct(int $merchantId, int $productId)
    {
        return MerchantProduct::where('merchant_id', $merchantId)
            ->where('product_id', $productId)
            ->first();
    }
    public function updateStock(int $stock, int $merchantId,int $productId)
    {
        $merchant = $this->getByMerchantProduct($merchantId, $productId);
        if (!$merchant) {
            throw ValidationException::withMessages([
                'product_id' => 'Product not found for this merchant'
            ]);
        }
        $merchant->update(['stock' => $stock]);
        return $merchant;
    }
}
