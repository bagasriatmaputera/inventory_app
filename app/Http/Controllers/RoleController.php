<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //

    private RoleService $roleService;
    public function __construct(RoleService $roleService)
    {
        $this->$roleService = $roleService;
    }
    public function index(array $fields)
    {
        $fields = ['id', 'name'];
        $role = $this->roleService->getAll($fields);
        return response()->json(
            RoleResource::collection($role)
        );
    }
    public function show(int $id)
    {
        $fields = ['id', 'name'];
        try {
            $role = $this->roleService->getById($id, $fields);
            return response()->json(new RoleResource($role));
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Role not found'
            ]);
        }
    }
    public function store(RoleRequest $roleRequest)
    {
        $role = $this->roleService->create($roleRequest->validated());
        return response()->json(new RoleResource($role));
    }
    public function destroy(int $id)
    {
        try {
            $role = $this->roleService->delete($id);
            return response()->json([
                'message' => 'Role delete successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Role not found'
            ]);
        }
    }
    public function update(int $id, RoleRequest $roleRequest)
    {
        try {
            $role = $this->roleService->update($id,$roleRequest->validated());
            return response()->json(new RoleResource($role));
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Role not found'
            ]);
        }
    }
}
