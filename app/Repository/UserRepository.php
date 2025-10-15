<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Arr;

class UserRepository
{
    public function getAll(array $fields)
    {
        return User::select($fields)->latest()->pagination(50);
    }
    public function getById(int $id, array $fields)
    {
        return User::findOrFail($id)->select($fields);
    }
    public function create(array $data)
    {
        return User::create($data);
    }
    public function update(int $id, array $data)
    {
        $user = $this->getById($id, $fields ?? ['*']);
        return $user->update($data);
    }
    public function delete(int $id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}
