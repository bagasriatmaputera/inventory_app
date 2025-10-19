<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRoleRequest;
use App\Services\UserRoleService;

class UserRoleController
{
    private UserRoleService $userRoleService;
    public function __construct(UserRoleService $userRoleService)
    {
        $this->userRoleService = $userRoleService;
    }
    public function assignRoleToUser(UserRoleRequest $userRoleRequest)
    {
        $role = $this->userRoleService->assignRoleToUser(
            $userRoleRequest->validated()['user_id'],
            $userRoleRequest->validated()['role_id']
        );
        return response()->json([
            'message' => 'Role assigned successfully',
            'data' => $role
        ]);
    }
    public function removeRoleToUser(UserRoleRequest $userRoleRequest)
    {
        $role = $this->userRoleService->removeRoleToUser(
            $userRoleRequest->validated()['user_id'],
            $userRoleRequest->validated()['role_id']
        );
        return response()->json([
            'message' => 'Role remove successfully',
            'data' => $role
        ]);
    }
    public function listRoleUser(int $userId){
        try {
            $roles = $this->userRoleService->listUserRoles($userId);
            return response()->json([
            'user_id' => $userId,
            'role' => $roles
        ]);
        } catch (\Throwable $th) {
            return response()->json([
            'message' => 'User not found',
        ]);
        }
    }
}
