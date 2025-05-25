<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
class SubCategoryDemoExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection([
            ['category', 'name'],

        ]);
    }
    public function headings(): array
    {
        return ['category', 'name']; // Column headers for the demo file
    }
}
