<?php

namespace App\Http\Controllers\Api\Products\Variation;

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
class VariationController extends Controller
{
     public function storeVariation(Request $request, ImageOptimizerService $imageService)
    {
        // dd($request->all());
        $posSetting =  PosSetting::first(); //
        // dd($request->all());
        $product = Variation::where('product_id', $request->productId)
            ->where('status', 'default')
            ->firstOrFail();

        $color = $request->input('color', []);
        $cost_price = $request->input('cost_price', []);
        $b2b_price = $request->input('b2b_price', []);
        $b2c_price = $request->input('b2c_price', []);
        $model_no = $request->input('model_no', []);
        $quality = $request->input('quality', []);
        $size = $request->input('variation_size', []);
        $images = $request->file('image', []);
        $current_stock = $request->input('current_stock', []);
        $manufacture_date = $request->input('manufacture_date', []);
        $expiry_date = $request->input('expiry_date', []);
        $low_stock_input = $request->input('low_stock_alert', []);

        // Loop through the arrays and insert each service
        // dd($request->all());
        foreach ($cost_price ?? 0 as $key => $price) {
            $imageName = null;

            // Handle image upload for this variation
            if (isset($images[$key]) && $images[$key]->isValid()) {
                $destinationPath = public_path('uploads/products');
                $imageName = $imageService->resizeAndOptimize($images[$key], $destinationPath);
            }
            // Generate a unique barcode for this variation
            $barcodePrefix = strtoupper(substr($product->product->name, 0, 2)); // Take the first 2 characters and convert to uppercase
            $uniquePart = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT); // Generate a 6-digit number with leading zeros if needed
            $barcode = $barcodePrefix . $uniquePart;

            $lowStockAlert = isset($low_stock_input[$key]) ? $low_stock_input[$key] : ($posSetting->low_stock ?? null);
            $variation =  Variation::create([
                'product_id' => $request->productId,
                'color' => $color[$key] ?? null,
                'cost_price' => $cost_price[$key] ??  $product->cost_price,
                'b2b_price' => $b2b_price[$key] ?? $product->b2b_price,
                'b2c_price' => $b2c_price[$key] ?? $product->b2c_price,
                'size' => $size[$key] ?? null,
                'model_no' => $model_no[$key] ?? null,
                'quality' => $quality[$key] ?? null,
                'image' => $imageName,
                'barcode' => $barcode, // Assign the unique barcode
                'low_stock_alert' =>  $lowStockAlert,
            ]);

            if (isset($current_stock[$key]) && $current_stock[$key] > 0) {
                Stock::create([
                    'branch_id' => Auth::user()->branch_id??$request->branch_id,
                    'product_id' => $request->productId,
                    'variation_id' => $variation->id,
                    'stock_quantity' => $current_stock[$key],
                    'is_Current_stock' => 1,
                    'status' => 'available',
                    'manufacture_date' => !empty($manufacture_date[$key]) ? date('Y-m-d', strtotime($manufacture_date[$key])) : null,
                    'expiry_date' => !empty($expiry_date[$key]) ? date('Y-m-d', strtotime($expiry_date[$key])) : null,
                ]);
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Variation added successfully!',
        ]);
    }


 public function findVariant(Request $request, $id)
    {
        $partyWaysRateKit = PosSetting::first();
        $customerId =  $request->selectedCustomerId;
        if ($request->isProduct) {
            $product = Product::findOrFail($id);
            $variant = Variation::with('product.productUnit', "variationSize", 'stocks', 'colorName')->findOrFail($product->id);

            $totalVariationStock = $variant->stocks->sum('stock_quantity');
            $varationStockCurrent = $variant->stocks->where('is_Current_stock', 1)->first($id);
        } else {

            //Before
            // $saleItemsPrice = SaleItem::where('variant_id', $id)
            // ->whereHas('saleId', function ($query) use ($customerId) {
            //     $query->where('customer_id', $customerId);
            // })
            // ->latest()
            // ->take(5)
            // ->get(['id', 'sale_id', 'product_id', 'variant_id','rate','qty',]);

            //After
            $saleItemsPrice = SaleItem::where('variant_id', $id)
                ->whereHas('saleId', function ($query) use ($customerId) {
                    $query->where('customer_id', $customerId);
                })
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id') // Join with the sales table
                ->latest('sale_items.created_at') // Ensure latest is based on sale_items
                ->take(5)
                ->get([
                    'sale_items.id',
                    'sale_items.sale_id',
                    'sale_items.product_id',
                    'sale_items.variant_id',
                    'sale_items.rate',
                    'sale_items.qty',
                    'sales.sale_date' // Include sale_date from sales table
                ]);

            // dd($saleItems);
            $variant = Variation::with('product.productUnit', "variationSize", 'stocks', 'colorName')->findOrFail($id);
            $totalVariationStock = $variant->stocks->sum('stock_quantity');
            $varationStockCurrent = $variant->stocks->where('is_Current_stock', 1)->first();
            // dd( $varationStockCurrent );
        }

        // dd($variant);
        return response()->json([
            'status' => 200,
            'variant' => $variant,
            'saleItemsPrice' => $saleItemsPrice,
            'totalVariationStock' => $totalVariationStock,
            'varationStockCurrent' => $varationStockCurrent,
        ]);
    }


 public function updateVariation(Request $request, ImageOptimizerService $imageService)
    {
        //    dd($request->all());
        $product = Variation::where('product_id', $request->product_id)
            ->where('status', 'default')
            ->firstOrFail();

        // Extract input data
        $variationIds = $request->input('variation_id', []); // For update
        $colors = $request->input('color', []);
        $costPrices = $request->input('cost_price', []);
        $b2bPrices = $request->input('b2b_price', []);
        $b2cPrices = $request->input('b2c_price', []);
        $modelNos = $request->input('model_no', []);
        $qualities = $request->input('quality', []);
        $sizes = $request->input('size', []);
        $images = $request->file('image', []);
        $currentStocks = $request->input('current_stock', []);

        // Loop through the input data
        foreach ($costPrices as $key => $costPrice) {
            $imageName = null;

            if (isset($images[$key]) && $images[$key]->isValid()) {
                $destinationPath = public_path('uploads/products');
                $imageName = $imageService->resizeAndOptimize($images[$key], $destinationPath);
            } elseif (!empty($variationIds[$key])) {
                // Retain the existing image if no new image is uploaded
                $existingVariation = Variation::find($variationIds[$key]);
                $imageName = $existingVariation ? $existingVariation->image : null;
            }

            // Generate a unique barcode for new variations
            $barcode = null;
            if (empty($variationIds[$key])) { // Only for new variations
                $barcodePrefix = strtoupper(substr($product->product->name, 0, 2)); // First 2 characters of product name
                $uniquePart = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT); // 6-digit random number
                $barcode = $barcodePrefix . $uniquePart;
            }

            // Update or create the variation
            $variation = Variation::updateOrCreate(
                ['id' => $variationIds[$key] ?? null], // Check if variation ID exists
                [
                    'product_id' => $request->product_id,
                    'color' => $colors[$key] ?? null,
                    'cost_price' => $costPrice ?? $product->cost_price,
                    'b2b_price' => $b2bPrices[$key] ?? $product->b2b_price,
                    'b2c_price' => $b2cPrices[$key] ?? $product->b2c_price,
                    'size' => $sizes[$key] ?? null,
                    'model_no' => $modelNos[$key] ?? null,
                    'quality' => $qualities[$key] ?? null,
                    'image' => $imageName,
                    'barcode' => $barcode ?? Variation::find($variationIds[$key])->barcode, // Keep existing barcode for updates
                ]
            );

            // Handle stock for the variation
            if (isset($currentStocks[$key]) && $currentStocks[$key] > 0) {
                Stock::updateOrCreate(
                    ['variation_id' => $variation->id],
                    [
                        'branch_id' => Auth::user()->branch_id??$request->branch_id,
                        'product_id' => $request->product_id,
                        'stock_quantity' => $currentStocks[$key],
                        'status' => 'available',
                    ]
                );
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Variations saved successfully!',
        ]);
    }



  public function deleteVariation($id)
    {
        $variation = Variation::findOrFail($id);
        // dd($variation);
        if ($variation && $variation->status !== 'default') {
            // Delete related stock data
            $variation->stocks()->delete();
            // Delete the variation
            $variation->delete();
            return response()->json(['status' => 200, 'message' => 'Variation deleted successfully.']);
        }

        // If the status is default or variation is not found
        return response()->json([
            'status' => 400, // Bad request
            'message' => $variation ? 'Cannot delete a variation with default status.' : 'Variation not found.'
        ]);
    }


   public function printAllBarcodes(Request $request)
    {
        // Get the variant data from the request
        try{
        $variantsData = $request->input('variants');

        // Fetch all variants and their quantities
        $variants = [];
        foreach ($variantsData as $data) {
            $variant = Variation::find($data['id']);
            if ($variant) {
                $variants[] = [
                    'variant' => $variant,
                    'quantity' => $data['quantity'] ?? 1, // Default to 1 if quantity is missing
                ];
            }
        }

        // // Pass the variants data to the view
        // return view('pos.products.product.product-barcode-all', compact('variants'));

        return response()->json([
            'status' => 200,
            'message' => 'Barcode printed successfully!',
            'variants' => $variants
        ]);

    }
    catch(\Exception $e){
        return response()->json([
            'status' => 400,
            'message' => $e->getMessage()
        ]);
    }
}


public function variationStatus($id)
    {

        // dd($id);
        try{
       
        $variation = Variation::findOrFail($id);
        //  dd($variation);
        // Get current status
        $currentStatus = $variation->productStatus;

        // Toggle status
        $newStatus = $currentStatus === 'active' ? 'inactive' : 'active';

        // Update the variation
        $variation->update(['productStatus' => $newStatus]);

        return response()->json([
            'success' => true,
            'newStatus' => $newStatus,
            'message' => 'Variation status updated successfully'
        ]);
    }
    catch(\Exception $e){
        return response()->json([
            'status' => 400,
            'message' => $e->getMessage()
        ]);
    }
}




}
