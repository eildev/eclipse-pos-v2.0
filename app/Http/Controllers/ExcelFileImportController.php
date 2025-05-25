<?php

namespace App\Http\Controllers;

use App\Imports\BrandImport;
use App\Imports\CategoryImport;
use App\Imports\CustomerImport;
use App\Models\PosSetting;
use App\Models\Product;
use App\Models\PromotionDetails;
// use Validator;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Imports\SubcategoryImport;
use App\Imports\SupplierImport;
use App\Jobs\ImportExcelDataJob;
use DataTables;
use Illuminate\Http\Request;

class ExcelFileImportController extends Controller
{
    public function importProductPage()
    {
        return view('pos.excel-import.excel-import-page');
    }

    /////////////////////// Products Import Data //////////////////////

    public function productImportExcelData(Request $request)
    {
        $request->validate([
            'import_file' => [
                'required',
                'file'
            ]
        ]);

        // try {
            // Attempt to import the Excel file
            Excel::import(new ProductsImport, $request->file('import_file'));

            // Success notification
            $notification = array(
                'message' => 'Products imported successfully.',
                'alert-type' => 'info'
            );
        // } catch (\Exception $e) {
        //     // Handle any errors that occurred during the import
        //     $notification = array(
        //         'warning' => 'Error importing products: ' . $e->getMessage(),
        //         'alert-type' => 'info'
        //     );
        // }

        return redirect()->back()->with($notification);
    }




    ///////////////////////Brand Import Data //////////////////////

    public function importBrandExcelData(Request $request)
    {
        $request->validate([
            'brand-import_file' => [
                'required',
                'file'
            ]
        ]);

        Excel::import(new BrandImport, $request->file('brand-import_file'));
        $notification = array(
            'message' => 'Brand imported successfully.',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }

    /////////////////////// Category Import Data //////////////////////

    public function importCategoryExcelData(Request $request)
    {
        $request->validate([
            'category-import_file' => [
                'required',
                'file'
            ]
        ]);
        Excel::import(new CategoryImport, $request->file('category-import_file'));
        $notification = array(
            'message' => 'Category imported successfully.',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }

        /////////////////////// Sub Category Import Data //////////////////////

        public function importSubcategoryExcelData(Request $request)
        {
            Excel::import(new SubcategoryImport, $request->file('subcategory-import_file'));
            $notification = array(
                'message' => 'SubCategory imported successfully.',
                'alert-type' => 'info'
            );
            return redirect()->back()->with($notification);
        }

  /////////////////////// Supplier Import Data //////////////////////
        public function importSupplierExcelData(Request $request)
        {
            $request->validate([
                'supplier-import_file' => [
                    'required',
                    'file'
                ]
            ]);

                // Attempt to import the Excel file
                Excel::import(new SupplierImport, $request->file('supplier-import_file'));

                // Success notification
                $notification = array(
                    'message' => 'Supplier imported successfully.',
                    'alert-type' => 'info'
                );

            return redirect()->back()->with($notification);
        }
  /////////////////////// Customer Import Data //////////////////////
        public function importCustomerExcelData(Request $request)
        {
            $request->validate([
                'customer-import_file' => [
                    'required',
                    'file'
                ]
            ]);

                // Attempt to import the Excel file
                Excel::import(new CustomerImport, $request->file('customer-import_file'));

                // Success notification
                $notification = array(
                    'message' => 'Customer imported successfully.',
                    'alert-type' => 'info'
                );

            return redirect()->back()->with($notification);
        }
}
