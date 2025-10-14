<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Repository\ProductRepository;
use App\Services\ProductServices;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductServices $productServices;
    public function __construct(ProductServices $productServices){
        $this->$productServices = $productServices;
    }
    public function index(){
        $product = $this->productServices->getAll($fields ?? ['*']);
        return response()->json(ProductResource::collection($product));
    }
    public function show($id){
        try {
            $product = $this->productServices->getById($id, $fields ?? ['*']);
            return response()->json(new ProductResource($product));
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Product not found'
            ],404);
        }
    }
    public function store(ProductRequest $request){
        $validated = $request->validated();
        $product = $this->productServices->create($validated);
        return response()->json(new ProductResource($product));
    }
    public function update (int $id, ProductRequest $request){
        try {
            $product = $this->productServices->update($id,$request->validated());
            return response()->json(new ProductResource($product));
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Product not found'
            ],404);
        }
    }
    public function destroy(int $id){
        try {
            $this->productServices->delete($id);
            return response()->json([
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Product not found'
            ],401);
        }
    }
}
