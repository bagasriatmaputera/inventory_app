<?php

namespace App\Services;

use App\Repository\RoleRepository;

class RoleService
{
    private RoleRepository $roleRepository;
    public function __construct(RoleService $roleService)
    {
        $this->$roleService = $roleService;
    }
    public function getAll(array $fields)
    {
        $fields = ['id', 'name'];
        return $this->roleRepository->getAll($fields);
    }
    public function getById(int $id, array $fields)
    {
        $fields = ['id', 'name'];
        return $this->roleRepository->getById($id, $fields);
    }
    public function create($data)
    {
        return $this->roleRepository->create($data);
    }
    public function update(int $id, array $data)
    {
        return $this->roleRepository->update($id, $data);
    }
    public function delete(int $id)
    {
        return $this->roleRepository->delete($id);
    }
}
