<?php

namespace App\Http\Controllers\Api\Products;

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
class ApiProductController extends Controller
{

     public function index()
    {
        // dd('hello');

        return view('pos.products.product.product');
    }


     public function store(Request $request, ImageOptimizerService $imageService)
    {
        // dd($request->all());


        try{
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category_id' => 'required|integer',
            'unit' => 'required',
            'size' => 'required',
            'cost_price' => 'required',
            'b2b_price' => 'nullable|numeric',
            'b2c_price' => 'nullable|numeric',
        ], [
            'b2b_price.required_without' => 'Either B2B price or B2C price is required.',
            'b2c_price.required_without' => 'Either B2B price or B2C price is required.',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (empty($request->b2b_price) && empty($request->b2c_price)) {
                $validator->errors()->add('b2b_price', 'Either B2B price or B2C price must be provided.');
                $validator->errors()->add('b2c_price', 'Either B2B price or B2C price must be provided.');
            }
        });
        if ($validator->passes()) {
            $product = new Product;
            $product->name =  $request->name;
            $product->category_id =  $request->category_id;
            $product->subcategory_id  =  $request->subcategory_id ?? null;
            $product->brand_id  =  $request->brand_id;
            $product->unit  =  $request->unit;
            $product->description  =  $request->description;
            $product->save();
            // product variations
            $productvariations = new Variation();
            $barcodePrefix = strtoupper(substr($request->name, 0, 2)); // Take the first 2 characters and convert to uppercase
            $uniquePart = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT); // Generate a 6-digit number with leading zeros if needed
            $posSetting =  PosSetting::first(); //
            $lowStockAlert = $request->low_stock_alert ? $request->low_stock_alert : $posSetting->low_stock ?? null;
            $productvariations->barcode = $barcodePrefix . $uniquePart;
            $productvariations->product_id = $product->id;
            $productvariations->color  =  $request->color;
            $productvariations->cost_price  =  $request->cost_price;
            $productvariations->b2c_price  =  $request->b2c_price;
            $productvariations->b2b_price  =  $request->b2b_price;
            $productvariations->size  =  $request->size;
            $productvariations->model_no  =  $request->model_no;
            $productvariations->quality  =  $request->quality;
            $productvariations->origin  =  $request->origin;
            $productvariations->low_stock_alert  = $lowStockAlert;

            $productvariations->status  = 'default';
            if ($request->hasFile('image')) {
                $destinationPath = public_path('uploads/products');
                $imageName = $imageService->resizeAndOptimize($request->file('image'), $destinationPath);
                $productvariations->image = $imageName;
            }
            $productvariations->save();
            if (!is_null($request->current_stock) || $request->current_stock > 0) {
                $stock = new Stock();
                $stock->branch_id = Auth::user()->branch_id??$request->branch_id;
                $stock->product_id =   $product->id;
                $stock->variation_id =   $productvariations->id;
                $stock->stock_quantity =  $request->current_stock;
                $stock->is_Current_stock =  1;
                $stock->status =  'available';
                $manufacture_date = $request->manufacture_date;
                $expiry_date = $request->expiry_date;
                // dd($manufacture_date,$expiry_date);
                $posSetting = PosSetting::first();
                // dd( $expiry_date);
                if ($posSetting->manufacture_date == 1 && !empty($manufacture_date)) {
                    $stock->manufacture_date = date('Y-m-d', strtotime($manufacture_date));
                }
                if ($posSetting->expiry_date == 1 && !empty($expiry_date)) {
                    $stock->expiry_date = date('Y-m-d', strtotime($expiry_date));
                }
                $stock->save();
            }

            if ($request->extra_field) {

                foreach ($request->extra_field_id as $key => $fieldId) {
                    $extraFieldInfo = Attribute::where('id', $fieldId)->first();

                    if (!isset($request->extra_field[$fieldId]) || $request->extra_field[$fieldId] === null) {
                        continue;
                    }
                    $data = $request->extra_field[$fieldId];

                    switch ($extraFieldInfo->data_type) {
                        case 'json':
                            if (is_array($data)) {
                                $storedValue = json_encode($data);
                            } else {
                                $storedValue = json_encode([$data]);
                            }
                            break;

                        case 'integer':

                            if (!is_numeric($data)) {
                                throw new \Exception("Invalid value! Expected a number.");
                            }
                            $storedValue = intval($data);
                            break;

                        case 'float':
                            if (!is_numeric($data)) {
                                throw new \Exception("Invalid value! Expected a number.");
                            }
                            $storedValue = floatval($data);
                            break;

                        case 'decimal':
                            if (!is_numeric($data)) {
                                throw new \Exception("Invalid value! Expected a number.");
                            }
                            $storedValue = number_format((float) $data, 2, '.', '');
                            break;

                        case 'double':
                            if (!is_numeric($data)) {
                                throw new \Exception("Invalid value! Expected a number.");
                            }
                            $storedValue = date('Y-m-d', strtotime($data));
                            break;
                        case 'date':
                            if (!strtotime($data)) {
                                throw new \Exception("Invalid value! Expected a valid date.");
                            }

                        default:
                            $storedValue = (string)$data;
                            break;
                    }


                    AttributeManage::create([
                        'extra_field_id' => $fieldId,
                        'value' => $storedValue,
                        'product_id' => $product->id,
                    ]);
                }
            }

            return response()->json([
                'status' => 200,
                'product' => $product,
                'message' => 'Product Save Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages()
            ]);
        }

    }
     catch (\Exception $e) {
        return response()->json([
            'status' => '500',
            'error' => $e->getMessage()
        ]);
    }
    }

   public function view()
    {
        // dd('hello');
        return view('pos.products.product.product-show');
    }



      public function edit($id)
    {
        // dd($id);
        try{
            $product = Product::with('defaultVariationsEdit')->findOrFail($id);
            return response()->json([
             'product' => $product
            ]);
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong');
        }
        // $product = Product::with('defaultVariationsEdit')->findOrFail($id);
        // return view('pos.products.product.product-edit', compact('product'));
    }

     public function update(Request $request, $id, ImageOptimizerService $imageService)
    {
        // dd($request->all());
        try{
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category_id' => 'required|integer',
            'unit' => 'required',
            'size' => 'required',
            'cost_price' => 'required',
            'b2b_price' => 'nullable|numeric',
            'b2c_price' => 'nullable|numeric',
        ], [
            'b2b_price.required_without' => 'Either B2B price or B2C price is required.',
            'b2c_price.required_without' => 'Either B2B price or B2C price is required.',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (empty($request->b2b_price) && empty($request->b2c_price)) {
                $validator->errors()->add('b2b_price', 'Either B2B price or B2C price must be provided.');
                $validator->errors()->add('b2c_price', 'Either B2B price or B2C price must be provided.');
            }
        });
        $product = Product::findOrFail($id);

        if ($validator->passes()) {

            $product->name =  $request->name;
            $product->category_id =  $request->category_id;
            $product->subcategory_id  =  $request->subcategory_id ?? null;
            $product->brand_id  =  $request->brand_id;
            $product->unit  =  $request->unit;
            $product->description  =  $request->description;
            $product->save();
            // product variations//
            $productvariations = Variation::where('product_id',  $product->id)->where('status', 'default')->first();
            // dd($productvariations);//
            if ($productvariations) {
                // Update the variation details
                $productvariations->color = $request->color;
                $productvariations->cost_price = $request->cost_price;
                $productvariations->b2c_price = $request->b2c_price;
                $productvariations->b2b_price = $request->b2b_price;
                $productvariations->size = $request->size;
                $productvariations->model_no = $request->model_no;
                $productvariations->quality = $request->quality;
                $productvariations->origin = $request->origin;

                if ($request->hasFile('image')) {
                    // Determine the path to the current image
                    $oldImagePath = public_path('uploads/products/' . $productvariations->image);

                    // Check if an old image exists and unlink it
                    if ($productvariations->image && file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }

                    // Process and save the new image
                    $destinationPath = public_path('uploads/products');
                    $imageName = $imageService->resizeAndOptimize($request->file('image'), $destinationPath);
                    $productvariations->image = $imageName;
                }

                // Save updated variation
                $productvariations->save();
            }

            return response()->json([
                'status' => 200,
                 'product' => $product,
                'message' => 'Product Update Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages()
            ]);
        }
    }
    catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'An error occurred while updating the product.',
            'error' => $e->getMessage(),
        ]);
    }
    }


      public function destroy($id)
    {


        try{
        $product = Product::findOrFail($id);
        // dd($product);
        if ($product->image) {
            $previousImagePath = public_path('uploads/product/') . $product->image;
            if (file_exists($previousImagePath)) {
                unlink($previousImagePath);
            }
        }
        $product->delete();
        // return back()->with('message', "Product deleted successfully");
        return response()->json([
            'status' => 200,
            'message' => 'Product deleted successfully',
        ]);
    }
    catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'An error occurred while deleting the product.',
            'error' => $e->getMessage(),
        ]);
    }
    }


    public function find($id)
    {
        $status = 'active';
        // Fetch product with its related unit
        // $product = Product::with('productUnit', 'stockQuantity', 'variations.variationSize', 'variations.product', 'variations.colorName')->findOrFail($id);
        //update for active status
        $product = Product::with([
            'productUnit',
            'stockQuantity',
            'variations' => function ($query) {
                $query->where('productStatus', 'active')->with(['variationSize', 'product', 'colorName', 'stocks']);
            }
        ])->findOrFail($id);

        $totalStock = $product->stockQuantity->sum('stock_quantity');

        // Check for active promotion details for the product
        $promotionDetails = PromotionDetails::whereHas('promotion', function ($query) use ($status) {
            return $query->where('status', '=', $status);
        })->where('promotion_type', 'products')->where('logic', 'like', '%' . $id . "%")->latest()->first();

        // dd($product);

        // If promotion details exist, return them along with the product and unit
        if ($promotionDetails) {
            return response()->json([
                'status' => '200',
                'data' => $product,
                'promotion' => $promotionDetails->promotion,
                'unit' => $product->productUnit ? $product->productUnit->name : null,  // Check for null
                'stock' => $totalStock,
            ]);
        } else {
            // If no promotion details exist, still return the product with the unit
            return response()->json([
                'status' => '200',
                'data' => $product,
                'unit' => $product->productUnit ? $product->productUnit->name : null,  // Check for null
                'stock' => $totalStock,
            ]);
        }
    }
 public function variantBarcode(Request $request, $id)
    {
         try{
         $variant = Variation::where('barcode', $id)->first();
         $quantity = $request->query('quantity', 1);

        return response()->json([
            'status' => 200,
            'variant' => $variant,
            'quantity' => $quantity,
        ]);
        }
        catch(\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching the product.',
                'error' => $e->getMessage(),
            ]);
        }

        // return view('pos.products.product.product-barcode', compact('variant', 'quantity'));

    }

    public function globalSearch(Request $request,$search_value)
    {

        $search_terms = array_filter(explode(' ', trim($search_value)));
        $customer_id = $request->customerId;
        $posSetting = PosSetting::first();
        //  dd($customer_id);
        $query = Variation::with(['product' => function ($query) {
            $query->select('id', 'name');
        }])
            ->select('id', 'product_id', 'b2b_price', 'b2c_price', 'color', 'size','cost_price')
            ->where('productStatus', 'active');


        $query->where(function ($q) use ($search_terms) {
            foreach ($search_terms as $term) {

                $q->where('color', 'LIKE', '%' . $term . '%')
                    ->orWhere('size', 'LIKE', '%' . $term . '%')

                    ->orWhereHas('product', function ($query) use ($term) {
                        $query->where('name', 'LIKE', '%' . $term . '%')

                            ->orWhereHas('category', function ($q) use ($term) {
                                $q->where('name', 'LIKE', '%' . $term . '%');
                            })

                            ->orWhereHas('subcategory', function ($q) use ($term) {
                                $q->where('name', 'LIKE', '%' . $term . '%');
                            })

                            ->orWhereHas('brand', function ($q) use ($term) {
                                $q->where('name', 'LIKE', '%' . $term . '%');
                            });
                    });
            }
        });


        $variations = $query->get();


        $formattedProducts = [];
        foreach ($variations as $variation) {
       $totalVariationStock = $variation->stocks->sum('stock_quantity');
        $salePartyKitPrice = SaleItem::where('variant_id', $variation->id)
                ->whereHas('saleId', function ($query) use ( $customer_id) {
                    $query->where('customer_id', $customer_id);
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
            $formattedProducts[] = [
                "variant_id" => $variation->id,
                'name' => $variation->product ? $variation->product->name : 'N/A',
                'totalVariationStock' => $totalVariationStock,
                'color' => $variation->color,
                'salePartyKitPrice' => $salePartyKitPrice,
                'cost_price' => $variation->cost_price,
                'b2b_price' => $variation->b2b_price,
                'b2c_price' => $variation->b2c_price,
                'size' => $variation->size,
                'variation_size' => $variation->variationSize->size,
                'variation_color' => $variation->colorName->name,
                'price' =>  $posSetting->sale_price_type === 'b2b_price' ? $variation->b2b_price : $variation->b2c_price,
                'product_id' => $variation->product_id,
                'variation_id' => $variation->id,
                'stock_quantity' => $variation->product ? ($variation->product->stock_quantity_sum_stock_quantity ?? 0) : 0
            ];
        }



        return response()->json([
            'products' => $formattedProducts,
            'pos_setting' => $posSetting,
            'status' => 200
        ]);
    }


     public function productVariationView($id)
    {
        try{
              $product = Product::with('variation')->findOrFail($id);
        //  dd($product);
        $defaultVariation = $product->variation->where('status', 'default')->first();
        // return view('pos.products.variations.variation_view', compact('product', 'defaultVariation'));
         return response()->json([
            'product' => $product,
            'defaultVariation' => $defaultVariation,
            'status' => 200
        ]);
        }
        catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => 'Something went wrong!',
            ]);


        }
        catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => 'Something went wrong!',
            ]);
        }

    }
 public function productStatus($id)
    {
        $product = Product::findOrFail($id);


        // dd($product);
        if (!$product->defaultVariations) {
            return response()->json(['success' => false, 'message' => 'No variations found'], 404);
        }
        $variations = Variation::where('product_id', $product->id)->get();
        // Toggle status
        $currentStatus = $variations->first()->productStatus;
        $newStatus = $currentStatus === 'active' ? 'inactive' : 'active';
        Variation::where('product_id', $id)->update(['productStatus' => $newStatus]);
        return response()->json([
            'success' => true,
            'newStatus' => $newStatus,
            'message' => 'Status updated successfully'
        ]);
    }


     public function productLedger($id)
    {
        // Check if the user is an admin (user id == 1)
        // if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
        //     // Fetch all products with stock quantity sum and relationships
        //     $products = Product::with(['category', 'brand', 'unit', 'subcategory', 'size'])
        //         ->withSum('stockQuantity', 'stock_quantity')
        //         ->latest();
        // } else {
        //     // Fetch only products for the logged-in user's branch with relationships
        //     $products = Product::where('branch_id', Auth::user()->branch_id)
        //         ->with(['category', 'brand', 'unit', 'subcategory', 'size'])
        //         ->withSum('stockQuantity', 'stock_quantity')
        //         ->latest();
        // }

        try{
        // Fetch product with its related unit
        $data = Product::with('stockQuantity', 'productUnit', 'saleItem')->findOrFail($id);

        // $data = Product::findOrFail($id)->withSum('stockQuantity', 'stock_quantity');
        // dd($product);
        $sales = SaleItem::where('product_id', $id)->get();
        $purchases = PurchaseItem::where('product_id', $id)->get();
        $variations = Variation::with('stocks', 'variationSize', 'colorName')->where('product_id', $id)->get();
        // Combine sales and purchases into one array
        $transactions = [];

        foreach ($sales as $sale) {
            $transactions[] = [
                'date' => $sale->created_at,
                'type' => 'sale', // Identifies as a sale
                'transaction' => $sale, // Store the sale object
            ];
        }

        foreach ($purchases as $purchase) {
            $transactions[] = [
                'date' => $purchase->created_at,
                'type' => 'purchase', // Identifies as a purchase
                'transaction' => $purchase, // Store the purchase object
            ];
        }

        // Sort by date
        usort($transactions, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        // Initialize report
        $reports = [];
        $balance = 0;

        // Loop through combined transactions
        foreach ($transactions as $item) {
            if ($item['type'] == 'sale') {
                // Sale transaction
                $sale = $item['transaction'];
                $reports[] = [
                    'date' => $sale->created_at,
                    'particulars' => 'Sale',
                    'stockIn' => 0, // No stock coming in during a sale
                    'stockOut' => $sale->qty, // Quantity sold
                    'balance' => $balance - $sale->qty, // Decrease balance
                ];
                $balance -= $sale->qty;
            } elseif ($item['type'] == 'purchase') {
                // Purchase transaction
                $purchase = $item['transaction'];
                $reports[] = [
                    'date' => $purchase->created_at,
                    'particulars' => 'Purchase',
                    'stockIn' => $purchase->quantity, // Quantity purchased
                    'stockOut' => 0, // No stock going out during a purchase
                    'balance' => $balance + $purchase->quantity, // Increase balance
                ];
                $balance += $purchase->quantity;
            }
        }



        return response()->json([
            'data' => $data,
            'reports' => $reports,
            'variations' => $variations,
            'status' => 200
        ]);
        // return view('pos.products.product-ledger.product-ledger', compact('data', 'reports', 'variations'));
    }
    catch(Exception $e){
        return response()->json([
            'message' => $e->getMessage(),
            'status' => 500
        ]);
    }

    }


     public function latestProduct()
    {
        $product = Product::latest()->first(); // Fetch the latest product
        return response()->json([
            'product' => $product, // Return as 'product', not 'products'
            'status' => 200
        ]);
    }


}
