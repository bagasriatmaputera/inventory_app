<?php

namespace App\Services;

use App\Repository\CategoryRepositories;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    private $categoryRepositories;
    public function __construct(CategoryRepositories $categoryRepositories)
    {
        $this->categoryRepositories = $categoryRepositories;
    }
    public function getAll(array $fields)
    {
        return $this->categoryRepositories->getAll($fields);
    }
    public function getById(int $id, array $fields)
    {
    return $this->categoryRepositories->getById($id, $fields ?? ['*']);
    }
    public function create(array $data)
    {
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->categoryRepositories->create($data);
    }
    public function update(int $id, array $data)
    {
        $fields = ['id', 'photo'];
        $category = $this->categoryRepositories->getById($id, $fields ?? ['*']);
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            if (!empty($category->photo)) {
                $this->deletePhoto($category->photo);
            }
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->categoryRepositories->update($id, $data);
    }
    public function delete(int $id)
    {
        $fields = ['id', 'photo'];
        $category = $this->categoryRepositories->getById($id, $fields);
        if ($category->photo) {
            $this->deletePhoto($category->photo);
        }
        $this->categoryRepositories->delete($id);
    }
    private function uploadPhoto(UploadedFile $photo)
    {
        return $photo->store('categories', 'public');
    }
    private function deletePhoto(string $photoPath)
    {
        $relativePath = 'categories/' . basename($photoPath);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}
