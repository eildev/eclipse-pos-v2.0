<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;
    protected $guarded = [];
    function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    function returnItem()
    {
        return $this->hasMany(ReturnItem::class, 'return_id', 'id');
    }

}
