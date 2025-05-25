<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
class ProductsDemoExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection([
            ['name', 'category','subcategory','brand','unit','description','barcode','cost_price','b2b_price','b2c_price','size','color','model_no','quality','origin','low_stock_alert','stock','manufacture_date','expiry_date'], // Column headings
        ]);
    }
    public function headings(): array
    {
        return ['name', 'category','subcategory','brand','unit','description','barcode','cost_price','b2b_price','b2c_price','size','color','model_no','quality','origin','low_stock_alert','stock','manufacture_date','expiry_date']; // Column headers for the demo file
    }
}
