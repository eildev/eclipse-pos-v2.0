<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLimit extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'user_limit'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
