<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'Default',
            'branch_id' => 1,
            'email' => 'default@gmail.com',
            'phone' => '017....',
            'opening_receivable' => 0,
            'opening_payable' => 0,
            'wallet_balance' => 0,
            'total_receivable' => 0,
            'total_payable' => 0,
            'party_type' => 'customer',
        ]);
    }
}
