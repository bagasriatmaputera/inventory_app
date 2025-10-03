<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseProductRequest;
use App\Services\WarehouseService;
use Illuminate\Http\Request;

class WarehouseProductController extends Controller
{
    private WarehouseService $warehouseService;
    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }
    public function attach(WarehouseProductRequest $request, int $warehouseId)
    {
        $validated = $request->validated();
        $this->warehouseService->attachProduct(
            $warehouseId,
            $validated['product_id'],
            $validated['stock']
        );
        return response()->json([
            'message' => 'Product attached successfully'
        ]);
    }
    public function detach(int $warehouseId, int $productId)
    {
        $this->warehouseService->detachProduct($warehouseId, $productId);
        return response()->json([
            'message' => 'Detached successfully'
        ]);
    }
    public function update(Request $request, int $warehouseId, int $productId)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:1'
        ]);

        $data = $this->warehouseService->updateProductStock(
            $warehouseId,
            $productId,
            $validated['stock'] // lebih spesifik
        );

        return response()->json([
            'message' => 'Stock updated successfully',
            'data' => $data
        ], 200);
    }
}
