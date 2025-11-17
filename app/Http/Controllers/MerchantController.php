<?php

namespace App\Http\Controllers;

use App\Http\Requests\MerchantRequest;
use App\Http\Requests\MerchantStoreRequest;
use App\Http\Resources\MerchantResource;
use App\Services\MerchantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantController extends Controller
{
    private MerchantService $merchantService;
    public function __construct(MerchantService $merchantService)
    {
        $this->merchantService = $merchantService;
    }
    public function index()
    {
        $fields = ['*'];
        $merchant = $this->merchantService->getAll($fields);
        return response()->json(MerchantResource::collection($merchant));
    }
    public function show(int $id)
    {
        $fields = ['*'];
        try {
            $merchant = $this->merchantService->getById($id, $fields);
            return response()->json(new MerchantResource($merchant));
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Merchant not found'
            ]);
        }
    }
    public function store(MerchantRequest $resquest)
    {
        $merchant = $this->merchantService->create($resquest->validated());
        return response()->json(new MerchantResource($merchant));
    }
    public function update(int $id, MerchantRequest $resquest)
    {
        try {
            $merchant = $this->merchantService->update($id, $resquest->validated());
            return response()->json(new MerchantResource($merchant));
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Merchant not found ' . $th->getMessage()
            ]);
        }
    }
    public function destroy(int $id)
    {
        try {
            $this->merchantService->delete($id);
            return response()->json([
                'Success' => 'Merchant deleted'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Merchant not found'
            ], 404);
        }
    }
    public function getMyMerchantProfile()
    {
        $merchantProfile = Auth::id();
        try {
            $merchant = $this->merchantService->getByKeeper($merchantProfile);
            return response()->json(new MerchantResource($merchant));
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Merchant Profil not found'
            ], 404);
        }
    }
}
