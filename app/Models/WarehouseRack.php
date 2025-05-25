<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseRack extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function warehouse(){
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
    public function stock(){
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
    }
}
