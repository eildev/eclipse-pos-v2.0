<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
class SupplierDemoExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection([
            ['name', 'branch_id','email','phone','address','wallet_balance'],
        ]);
    }
    public function headings(): array
    {
        return ['name', 'branch_id','email','phone','address','wallet_balance',];
    }
}
