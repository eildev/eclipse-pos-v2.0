<?php

namespace App\Models\LoanManagement;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function bankAccounts()
    {
        return $this->belongsTo(Bank::class);
    } //
}
