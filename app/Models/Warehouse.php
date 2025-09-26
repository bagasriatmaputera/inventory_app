<?php

namespace App\Models;

use Illuminate\Container\Attributes\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage as FacadesStorage;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'adrress',
        'photo',
        'phone'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'warehouse_products')
            ->withPivot('stock')
            ->withTimestamps();
    }
    public function getPhotoAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return url(FacadesStorage::url($value));
    }
}
