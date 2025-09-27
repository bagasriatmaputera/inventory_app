<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'stock',
        'warehouse_id',
    ];
    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }
    public function products(){
        return $this->belongsTo(Product::class);
    }
}
