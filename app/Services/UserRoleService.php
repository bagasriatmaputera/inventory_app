<?php

namespace App\Services;

use App\Repository\UserRoleRepository;

class UserRoleService
{
    private UserRoleRepository $userRoleRepository;
    public function __construct(UserRoleRepository $userRoleRepository)
    {
        $this->userRoleRepository = $userRoleRepository;
    }
    public function assignRoleToUser(int $userId, int $roleId)
    {
        return $this->userRoleRepository->assignRoleToUser($userId, $roleId);
    }
    public function removeRoleToUser(int $userId, int $roleId)
    {
        return $this->userRoleRepository->removeRoleToUser($userId, $roleId);
    }
    public function listUserRoles(int $userId)
    {
        return $this->userRoleRepository->getUserRoles($userId);
    }
}
