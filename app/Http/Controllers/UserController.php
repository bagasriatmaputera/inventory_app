<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->$userService = $userService;
    }
    public function index(array $fields)
    {
        $fields = ['id', 'name', 'email', 'phone', 'photo'];
        $user = $this->userService->getAll($fields);
        return response()->json(UserResource::collection($user));
    }
    public function show(int $id, array $fields)
    {
        $fields = ['id', 'name', 'email', 'phone', 'photo'];
        try {
            $user = $this->userService->getById($id, $fields);
            return response()->json(new UserResource($user));
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'User not found'
            ]);
        }
    }
    public function store(UserRequest $userRequest)
    {
        $user = $this->userService->create($userRequest->validated());
        return response()->json(new UserResource($user));
    }
    public function update(int $id, UserRequest $userRequest)
    {
        try {
            $user = $this->userService->update($id, $userRequest->validated());
            return response()->json(new UserResource($user));
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'User not found'
            ]);
        }
    }
    public function destroy(int $id)
    {
        try {
            $user = $this->userService->delete($id);
            return response()->json([
                'message' => 'User deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'User not found'
            ]);
        }
    }
}
