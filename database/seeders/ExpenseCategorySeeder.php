<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;
class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expensCategorys = [
            [
            'id' => 1,
            'name' => 'Rent',
            ],
            [
            'id' => 2,
            'name' => 'Electricity',
            ],
            [
            'id' => 3,
            'name' => 'Paper Bill',
            ],
            [
            'id' => 4,
            'name' => 'Net Bill',
            ],
        ];
        foreach ($expensCategorys as $expensCategory) {
            ExpenseCategory::create($expensCategory);
        }
    }
}
