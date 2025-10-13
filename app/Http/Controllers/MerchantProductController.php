<?php

namespace App\Http\Controllers;

use App\Http\Requests\MerchantStoreRequest;
use App\Http\Requests\MerchantUpdateProductRequest;
use App\Services\MerchantProductService;
use Illuminate\Http\Request;

class MerchantProductController extends Controller
{
    private MerchantProductService $merchantProductService;
    public function __construct(MerchantProductService $merchantProductService)
    {
        $this->$merchantProductService = $merchantProductService;
    }
    public function store(MerchantStoreRequest $request, int $merchant)
    {
        $validated = $request->validated();
        $validated['merchant_id'] = $merchant;
        $merchantProduct = $this->merchantProductService->assignProductToMerchant($validated);
        return response()->json([
            'message' => 'Success',
            'data' => $merchantProduct
        ]);
    }
    public function update(MerchantUpdateProductRequest $requset, int $merchantId, int $productId)
    {
        $validated = $requset->validated();
        $merchantProduct = $this->merchantProductService->updateStock(
            $merchantId,
            $productId,
            $validated['warehouse_id'],
            $validated['stock']
        );
        return response()->json([
            'message' => 'Update Successfully',
            'data' => $merchantProduct
        ]);
    }
    public function destroy(int $merchantId, int $productId)
    {
        $merchantProduct = $this->merchantProductService->removeProductFromMerchant($merchantId, $productId);
        return response()->json([
            'message' => 'Delete Successfully'
        ]);
    }
}
