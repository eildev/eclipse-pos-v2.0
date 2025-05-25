<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    use HasFactory;
    protected $guarded = [];

    function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id', 'id');
    }
    function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}
