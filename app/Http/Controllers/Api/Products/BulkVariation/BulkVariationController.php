<?php

namespace App\Http\Controllers\Api\Products\BulkVariation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PromotionDetails;
use Illuminate\Support\Facades\Validator;

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
class BulkVariationController extends Controller
{
     public function bulkVariationView()
    {
        return view('pos.products.variation.bulk_variation_view');
    }


      public function bulkVariationData()
    {
        $product_variations = Variation::with('product', 'stocks', 'variationSize')->orderBy('product_id')->get();
        return response()->json([
            'product_variations' => $product_variations,
            'status' => 200
        ]);
    }


     public function bulkVariationUpdate(Request $request)
    {

        //   dd($request->all());
        foreach ($request->selectVariation as $variationData) {
            $variation = Variation::find($variationData['variationId']);

            if ($variation) {
                $variation->cost_price = $variationData['costPrice'];
                $variation->b2b_price = $variationData['b2bPrice'];
                $variation->b2c_price = $variationData['b2cPrice'];
                $variation->product_id = $variationData['productId'];
                //  dd($variation);
                $variation->save();

                // Handle stock update
                $variationStock = Stock::where('variation_id', $variationData['variationId'])->first();

                if ($variationStock) {
                    continue;
                } else {
                    $stock = new Stock();
                    $stock->variation_id = $variationData['variationId'];
                    $stock->product_id = $variationData['productId'];
                    $stock->branch_id = Auth::user()->branch_id;
                    $stock->stock_quantity = $variationData['stock'];
                    $stock->is_Current_stock = 1;
                    $stock->save();
                }
            }
        }

        return response()->json(['status' => 200, 'message' => 'Variations updated successfully']);
    }
}
