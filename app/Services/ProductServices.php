<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

class ProductServices
{
    private ProductRepository $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->$productRepository = $productRepository;
    }
    public function getAll(array $fields)
    {
        return $this->productRepository->getAll($fields ?? '*');
    }
    public function getById(int $id, array $fields)
    {
        return $this->productRepository->getById($id, $fields ?? '*');
    }
    public function create($data)
    {
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->productRepository->create($data);
    }
    public function update(int $id, array $data)
    {
        $fields = ['id', 'photo'];
        $product = $this->getById($id, $fields ?? ['*']);
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            if (!isEmpty($data['photo'])) {
                $this->deletePhoto($product->photo);
            }
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->productRepository->update($id, $data);
    }
    public function delete(int $id)
    {
        $product = $this->getById($id, $fields ?? ['*']);
        if ($product->photo) {
            $this->deletePhoto($product->photo);
        }
        return $this->productRepository->delete($id);
    }
    private function uploadPhoto(UploadedFile $photo)
    {
        return $photo->store('products', 'public');
    }
    private function deletePhoto(string $photoPath)
    {
        $relativePath = 'products/' . basename($photoPath);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}
