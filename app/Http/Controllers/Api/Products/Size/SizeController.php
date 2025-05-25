<?php

namespace App\Http\Controllers\Api\Products\Size;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PromotionDetails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Psize;
use App\Models\PurchaseItem;
use App\Models\SaleItem;
use App\Models\Stock;
use App\Models\Variation;
use App\Models\Attribute;
use App\Services\ImageOptimizerService;
use PHPUnit\TextUI\Configuration\Variable;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AttributeManage;
use App\Models\Color;
use App\Models\PosSetting;
use App\Models\ProductExtraField;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Settings;
use Illuminate\Support\Facades\Log;
use Exception;

class SizeController extends Controller
{
     public function latestProductSize()
    {
        $product = Product::latest()->first();
        $variation  = Variation::where('product_id', $product->id)->where('status', 'default')->first();
        $sizesIdGet = Psize::where('id', $variation->size)->first(); // Fetch the size based on the variation
        $categoryId = $sizesIdGet->category_id;
        $sizes = Psize::where('category_id', $categoryId)->get();
        // dd( $sizes);
        return response()->json([
            'sizes' => $sizes,
            'status' => 200
        ]);
    }

      public function variationProductSize($id)
    {
        $product = Product::findOrfail($id);
        $variation  = Variation::where('product_id', $product->id)->where('status', 'default')->first();
        $sizesIdGet = Psize::where('id', $variation->size)->first(); // Fetch the size based on the variation
        $categoryId = $sizesIdGet->category_id;
        $sizes = Psize::where('category_id', $categoryId)->get();
        //  dd( $sizes);
        return response()->json([
            'sizes' => $sizes,
            'status' => 200
        ]);
    }


    public function editProductSize(Request $request, $id)
    {
        $product = Product::where('id', $id)->first();
        $variation  = Variation::where('product_id', $product->id)->where('status', 'default')->first();
        $sizesIdGet = Psize::where('id', $variation->size)->first(); 
        $categoryId = $sizesIdGet->category_id;
        $sizes = Psize::where('category_id', $categoryId)->get();
        // dd( $sizes);
        return response()->json([
            'sizes' => $sizes,
            'status' => 200
        ]);
    }
}
