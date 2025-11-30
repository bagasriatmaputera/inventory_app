<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\MerchantProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('warehouses', WarehouseController::class);
Route::apiResource('merchants', MerchantController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('roles', RoleController::class);

// User Management Role
Route::post('/users/role',[UserRoleController::class,'assignRoleToUser']);
Route::get('/users/role/{userId}',[UserRoleController::class,'listRoleUser']);
Route::post('/users/remove-role/',[UserRoleController::class,'removeRoleToUser']);

Route::post('/warehouse/{warehouse}/products', [WarehouseProductController::class, 'attach']);
Route::delete('/warehouse/{warehouse}/products/{product}', [WarehouseProductController::class, 'detach']);
Route::patch('/warehouse/{warehouse}/products/{product}', [WarehouseProductController::class, 'update']);
Route::post('/merchants/{merchants}/products', [MerchantProductController::class, 'store']);
Route::delete('/merchants/{merchants}/products/{product}', [MerchantProductController::class, 'destroy']);
Route::patch('/merchants/{merchants}/products/{product}', [MerchantProductController::class, 'update']);
