<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;
    protected $guarded = [];
    function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    function rack()
    {
        return $this->belongsTo(WarehouseRack::class);
    }
    function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function userName()
    {
        return $this->belongsTo(User::class, 'adjusted_by', 'id'); // Adjust 'user_id' if needed
    }
    public function items()
    {
        return $this->hasMany(StockAdjustmentItems::class, 'adjustment_id', 'id');
    }
}
