<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function salesCustomer()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }

}
