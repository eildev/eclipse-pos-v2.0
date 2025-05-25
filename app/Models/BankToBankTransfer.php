<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankToBankTransfer extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function fromBank() {
        return $this->belongsTo(Bank::class, 'from', 'id');
    }

    public function toBank() {
        return $this->belongsTo(Bank::class, 'to', 'id');
    }
}
