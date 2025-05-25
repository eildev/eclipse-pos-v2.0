<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
class CategoryDemoExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection([
            ['name'], // Column headings

        ]);
    }
    public function headings(): array
    {
        return ['name']; // Column headers for the demo file
    }
}
