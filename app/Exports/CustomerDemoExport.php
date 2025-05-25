<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
class CustomerDemoExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection([
            ['name', 'branch_id','email','phone','address','opening_payable','opening_receivable','wallet_balance','total_receivable','total_payable','party_type'],
        ]);
    }
    public function headings(): array
    {
        return  ['name', 'branch_id','email','phone','address','opening_payable','opening_receivable','wallet_balance','total_receivable','total_payable','party_type'];
    }
}
