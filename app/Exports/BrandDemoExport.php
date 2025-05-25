<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
class BrandDemoExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection([
            ['name',  'description'], // Column headings

        ]);
    }
    public function headings(): array
    {
        return ['name',  'description']; // Column headers for the demo file
    }
}
