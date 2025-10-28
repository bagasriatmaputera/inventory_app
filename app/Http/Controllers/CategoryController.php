<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        $fields = ['id', 'name', 'photo'];
        $category = $this->categoryService->getAll($fields);
        return response()->json(CategoryResource::collection($category));
    }
    public function show(int $id)
    {
        $fields = ['id', 'name', 'photo'];
        try {
            $category = $this->categoryService->getById($id, $fields);
            return response()->json(new CategoryResource($category));
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'category not found'
            ], 404);
        }
    }
    public function store(CategoryRequest $request)
    {
        try {
            $category = $this->categoryService->create($request->validated());
            return response()->json(new CategoryResource($category), 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
    public function update(int $id, CategoryRequest $request)
    {
        try {
            $category = $this->categoryService->update($id, $request->validated());
            return response()->json([
                'meesage' => 'Update Success',
                'data' => new CategoryResource($category)
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Category not found'
            ]);
        }
    }
    public function destroy(int $id)
    {
        try {
            $this->categoryService->delete($id);
            return response()->json([
                "success" => "Category deleted"
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Category not found"
            ], 401);
        }
    }
}
