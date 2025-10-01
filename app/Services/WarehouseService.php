<?php

namespace App\Services;

use App\Models\Warehouse;
use App\Repository\WarehouseRepositories;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class WarehouseService
{
    private $WarehouseRepository;
    public function __construct(WarehouseRepositories $warehouseRepositories)
    {
        $this->WarehouseRepository = $warehouseRepositories;
    }
    public function getAll($fields)
    {
        return $this->WarehouseRepository->getAll($fields);
    }
    public function getById(int $id, array $fields)
    {
        return $this->WarehouseRepository->getById($id, $fields);
    }
    public function store(array $data)
    {
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->WarehouseRepository->create($data);
    }
    public function update(int $id, array $data)
    {
        $fields = ['id', 'photo'];
        $warehouse = $this->WarehouseRepository->getById($id, $fields);
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            if (!empty($warehouse->photo)) {
                $this->deletePhoto($warehouse->photo);
            }
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->WarehouseRepository->update($id, $data);
    }
    public function delete(int $id){
        $fields = ['id', 'photo'];
        $warehouse = $this->WarehouseRepository->getById($id,$fields);
        if ($warehouse->photo) {
            $this->deletePhoto($warehouse->photo);
        }
        $this->WarehouseRepository->delete($id);
    }
    private function uploadPhoto(UploadedFile $photo)
    {
        return $photo->store('warehouses', 'public');
    }
    private function deletePhoto($stringPhoto)
    {
        $relativePath = 'warehouses' . basename($stringPhoto);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}
