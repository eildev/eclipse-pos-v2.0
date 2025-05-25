<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function variation()
    {
        return $this->hasOne(Variation::class);
    }
    function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'id');
    }
    function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
    function productUnit()
    {
        return $this->belongsTo(Unit::class, 'unit', 'id');
    }

    function damage()
    {
        return $this->hasMany(Damage::class);
    }
    //
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class, 'product_id');
    }

    public function stockQuantity()
    {
        return $this->hasMany(Stock::class, 'product_id');
    }

    public function variations()
    {
        return $this->hasMany(Variation::class, 'product_id');
    }
    public function defaultVariations()
    {
        return $this->hasOne(Variation::class)->where('status', 'default');
    }
    public function defaultVariationsEdit()
    {
        return $this->hasOne(Variation::class)->where('status', 'default');
    }
    public function saleItem()
    {
        return $this->hasMany(SaleItem::class, 'product_id');
    }

    public function product_extra_field_manage()
    {
        return $this->hasMany(AttributeManage::class, 'product_id', 'id');
    }
}
