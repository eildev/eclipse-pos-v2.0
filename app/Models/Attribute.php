<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product_extra_field_manage(){
        return $this->hasMany(AttributeManage::class,'extra_field_id','id');
    }

}
