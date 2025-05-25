<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Variation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'variation_id');
    }

    function variationSize() // Fix the spelling!
    {
        return $this->belongsTo(Psize::class, 'size', 'id');
    }

    function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function stockQuantity()
    {
        return $this->hasOne(Stock::class, 'variation_id');
    }
    // public function getTotalCostPriceAttribute()
    // {
    //     return $this->stock_quantity * $this->cost_price;
    // }

    // public function getTotalB2bPriceAttribute()
    // {
    //     return $this->stock_quantity * $this->b2b_price;
    // }

    // public function getTotalB2cPriceAttribute()
    // {
    //     return $this->stock_quantity * $this->b2c_price;
    // }

    public function saleItem()
    {
        return $this->hasMany(SaleItem::class, 'variant_id');
    }

    // ✅ Image Accessor
    // public function getImageUrlAttribute()
    // {
    //     $imagePath = public_path("uploads/products/{$this->image}");

    //     if ($this->image && File::exists($imagePath)) {
    //         return asset("uploads/products/{$this->image}");
    //     }

    //     return asset('dummy/image.jpg'); // Default image
    // }

        function colorName()
        {
            return $this->belongsTo(Color::class, 'color', 'id');
        }
}
