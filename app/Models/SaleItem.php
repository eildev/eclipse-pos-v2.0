<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    function variant()
    {
        return $this->belongsTo(Variation::class, 'variant_id', 'id');
    }
    function saleId()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }
}
