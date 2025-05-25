<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSale extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function customer() {
        return $this->belongsTo(Customer::class,'customer_id', 'id');
    }
    public function items() {
        return $this->hasMany(ServiceSaleItem::class,'service_sale_id', 'id');
    }
}
