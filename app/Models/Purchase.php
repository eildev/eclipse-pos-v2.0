<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $guarded = [];
    function supplier()
    {
        return $this->belongsTo(Customer::class, 'supplier_id', 'id');
    }
    function purchaseItem()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    function purchaseCostItems()
    {
        return $this->hasMany(PurchaseCostDetails::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'particulars')
            ->where('particulars', 'like', 'Purchase#%');
    }
}