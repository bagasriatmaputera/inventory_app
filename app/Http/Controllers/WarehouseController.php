<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Services\WarehouseService;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    private $WarehouseService;
    public function __construct(WarehouseService $warehouseService)
    {
        $this->WarehouseService = $warehouseService;
    }
    public function index()
    {
        $fields = ['id', 'name', 'photo'];
        $warehouse = $this->WarehouseService->getAll($fields ?? ['*']);
        return response()->json(WarehouseResource::collection($warehouse));
    }
    public function show(int $id)
    {
        $fields = ['id', 'name', 'photo', 'phone'];
        try {
            $warehouse = $this->WarehouseService->getById($id, $fields ?? ['*']);
            return response()->json(new WarehouseResource($warehouse));
        } catch (\Throwable $th) {
            return response()->json([
                "message" => 'Warehouse not found'
            ]);
        }
    }
    public function store(WarehouseRequest $request)
    {
        $warehouse = $this->WarehouseService->create($request->validated());
        return response()->json(new WarehouseResource($warehouse));
    }
    public function update(int $id, WarehouseRequest $request)
    {
        try {
            $warehouse = $this->WarehouseService->update($id, $request->validated());
            return response()->json(new WarehouseResource($warehouse));
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Warehouse not found'
            ]);
        }
    }
    public function destroy(int $id)
    {
        try {
            $this->destroy($id);
            return response()->json([
                'Success' => 'Warehouse deleted'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Warehouse not found'
            ]);
        }
    }
}
