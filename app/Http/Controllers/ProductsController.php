<?php

namespace App\Http\Controllers;

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

class ProductsController extends Controller
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
        return view('pos.products.product.product-show');
    }

    // public function getData(Request $request)
    // {
    //     // Check if the user is an admin (user id == 1)
    //     // if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
    //     //     // Fetch all products with stock quantity sum and relationships
    //     //     $products = Product::with(['category', 'brand', 'unit', 'subcategory', 'size','variations'])
    //     //         ->withSum('stockQuantity', 'stock_quantity')
    //     //         ->latest();

    //     // } else {
    //     // Fetch only products for the logged-in user's branch with relationships
    //     // $products = Product::with(['category', 'brand', 'unit', 'subcategory','variations','variations.size'])
    //     //     ->withSum('stockQuantity', 'stock_quantity')
    //     //     ->latest();
    //     //  }

    //     $products = Product::with(['category', 'brand', 'productUnit', 'subcategory', 'defaultVariations.variationSize'])
    //         ->withSum('stockQuantity', 'stock_quantity')
    //         ->latest()->get();
    //     // dd($products);
    //     // Check if the request is an AJAX call (DataTables request)
    //     if ($request->ajax()) {


    //         // dd($products->toArray());
    //         return DataTables::of($products)
    //             // ->addColumn("SL No", function ($product) {
    //             //     return $product->category->name ?? 'N/A';
    //             // })
    //             ->addColumn('category_name', function ($product) {
    //                 return $product->category->name ?? 'N/A'; // Show category name
    //             })
    //             ->addColumn('image', function ($product) {
    //                 // Get the image from the first variation, or use a default image
    //                 $variationImage = $product->defaultVariations->image ?? null;

    //                 // Build the image URL using the 'uploads/product/' path
    //                 $imagePath = public_path('uploads/products/' . $variationImage);
    //                 $imageUrl = (!empty($variationImage) && file_exists($imagePath))
    //                     ? asset('uploads/products/' . $variationImage)
    //                     : asset('dummy/image.jpg');

    //                 return '<img src="' . $imageUrl . '" alt="Variation Image" style="width: 50px; height: auto;">';
    //             })
    //             ->addColumn('barcode', function ($product) {
    //                 return $product->defaultVariations ? $product->defaultVariations->barcode : 'N/A'; // Handle null values
    //             })
    //             ->addColumn('brand_name', function ($product) {
    //                 return $product->brand ? $product->brand->name : 'N/A'; // Show brand name
    //             })
    //             // Adding the column for subcategory name
    //             ->addColumn('subcategory_name', function ($product) {
    //                 return $product->subcategory ? $product->subcategory->name : 'N/A'; // Add subcategory name
    //             })
    //             // Adding the column for cost price
    //             ->addColumn('cost_price', function ($product) {
    //                 return $product->defaultVariations ? $product->defaultVariations->cost_price : 'N/A'; // Handle null values
    //             })
    //             ->addColumn('color', function ($product) {
    //                 return $product->defaultVariations ? $product->defaultVariations->color : 'N/A'; // Handle null values
    //             })
    //             // Adding the column for b2b price
    //             ->addColumn('b2b_price', function ($product) {
    //                 return $product->defaultVariations ? $product->defaultVariations->b2b_price : 'N/A'; // Handle null values
    //             })
    //             // Adding the column for b2c price
    //             ->addColumn('b2c_price', function ($product) {
    //                 return $product->defaultVariations ? $product->defaultVariations->b2c_price : 'N/A'; // Handle null values
    //             })
    //             // Adding the column for size name
    //             ->addColumn('size_name', function ($product) {
    //                 return $product->defaultVariations && $product->defaultVariations->size ? $product->defaultVariations->size->name : 'N/A';
    //             })
    //             // Adding the column for unit name
    //             ->addColumn('unit_name', function ($product) {
    //                 return $product->productUnit ? $product->productUnit->name : 'N/A'; // Handle null values
    //             })
    //             ->addColumn('action', function ($product) {
    //                 $viewBtn = '<a href="' . route('product.ledger', $product->id) . '" class="btn btn-sm btn-success">View</a>';
    //                 $barcodeBtn = '<a href="' . route('product.barcode', $product->id) . '" class="btn btn-sm btn-info">Barcode</a>';
    //                 $editBtn = '';
    //                 if (Auth::user()->can('products.edit')) {
    //                     $editBtn = '<a href="' . route('product.edit', $product->id) . '" class="btn btn-sm btn-primary">Edit</a>';
    //                 }
    //                 // $deleteBtn = '';
    //                 // if (Auth::user()->can('products.delete')) {
    //                 //     $deleteBtn = '<a href="'.route('product.destroy', $product->id).'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>';
    //                 // }
    //                 $deleteBtn = Auth::user()->can('products.delete')
    //                     ? '<a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="confirmDelete(' . $product->id . ')">Delete</a>'
    //                     : '';
    //                 return $barcodeBtn . ' ' . $viewBtn . ' ' . $editBtn . ' ' . $deleteBtn; // Concatenating the buttons
    //             })
    //             ->rawColumns(['image', 'action']) // Allow HTML in 'image' and 'action' columns
    //             ->make(true);
    //     }

    //     return view('pos.products.product.product-show');
    // }


    public function getData(Request $request)
    {

        $products = Product::with(['category', 'brand', 'productUnit', 'subcategory', 'defaultVariations', 'defaultVariations.variationSize'])
            ->withSum('stockQuantity', 'stock_quantity')
            ->latest()->get();
        // dd($products[0]->defaultVariations->barcode);
        // Check if the request is an AJAX call (DataTables request)
        if ($request->ajax()) {
            // dd($products->toArray());
            return DataTables::of($products)
                ->addColumn('image', function ($product) {
                    // Get the image from the first variation, or use a default image
                    $variationImage = $product->defaultVariations->image ?? null;

                    // Build the image URL using the 'uploads/product/' path
                    $imagePath = public_path('uploads/products/' . $variationImage);
                    $imageUrl = (!empty($variationImage) && file_exists($imagePath))
                        ? asset('uploads/products/' . $variationImage)
                        : asset('dummy/image.jpg');

                    return '<img src="' . $imageUrl . '" alt="Variation Image" style="width: 50px; height: auto;">';
                })
                ->addColumn('barcode', function ($product) {
                    return $product->defaultVariations ? $product->defaultVariations->barcode : 'N/A'; // Handle null values
                })
                ->addColumn('brand_name', function ($product) {
                    return $product->brand ? $product->brand->name : 'N/A'; // Show brand name
                })
                // Adding the column for subcategory name
                ->addColumn('subcategory_name', function ($product) {
                    return $product->subcategory ? $product->subcategory->name : 'N/A'; // Add subcategory name
                })
                // Adding the column for cost price
                ->addColumn('cost_price', function ($product) {
                    return $product->defaultVariations ? $product->defaultVariations->cost_price : 'N/A'; // Handle null values
                })
                // ->addColumn('color', function ($product) {
                //     return $product->defaultVariations ? $product->defaultVariations->colorName->name : 'N/A'; // Handle null values
                // })
                // ->addColumn('color', function ($product) {

                //     $variations = $product->variations;

                //     if ($variations->isEmpty()) {
                //         return 'N/A';
                //     }

                //     $colorCounts = $variations->groupBy('color')->map(function ($group) {
                //         $colorName = $group->first()->colorName ? $group->first()->colorName->name : 'Unknown';
                //         return $colorName . ' (' . $group->count() . ')';
                //     })->values()->implode(', ');

                //     return $colorCounts ?: 'N/A';
                // })
                ->addColumn('color', function ($product) {
                    $variations = $product->variations;

                    if ($variations->isEmpty()) {
                        return 'N/A';
                    }

                    $colorCount = $variations->filter(function ($variation) {
                        return !is_null($variation->colorName);
                    })->groupBy('colorName')->count();

                    return $colorCount > 0 ? $colorCount . ' Colors available' : 'N/A';
                })
                // Adding the column for b2b price
                ->addColumn('b2b_price', function ($product) {
                    return $product->defaultVariations ? $product->defaultVariations->b2b_price : 'N/A'; // Handle null values
                })
                // Adding the column for b2c price
                ->addColumn('b2c_price', function ($product) {
                    return $product->defaultVariations ? $product->defaultVariations->b2c_price : 'N/A'; // Handle null values
                })
                // Adding the column for size name
                ->addColumn('size_name', function ($product) {
                    return $product->defaultVariations && $product->defaultVariations->variationSize ? $product->defaultVariations->variationSize->size : 'N/A';
                })
                // Adding the column for unit name
                ->addColumn('unit_name', function ($product) {
                    // foreach($product->variations as $variation) {
                    // $abc =   $variation->stockQuantity->sum('stock_quantity');

                    // }
                    return $product->productUnit ? $product->productUnit->name : 'N/A'; // Handle null values
                })
                ->addColumn('quantity', function ($product) {
                    return $product->stockQuantity->sum('stock_quantity');
                })
                ->addColumn('action', function ($product) {
                    // $status = $product->defaultVariations ? ($product->defaultVariations->productStatus ?? 'N/A') : 'N/A';
                    // $buttonClass = $status === 'active' ? 'btn-success' : 'btn-danger';
                    // $productStatus = '<button type="button" class="btn btn-sm ' . $buttonClass . ' toggle-status-btn" data-id="' . $product->id . '" data-status="' . $status . '">' . $status . '</button>';
                    $status = $product->variations->isNotEmpty()
                        ? ($product->variations->contains('productStatus', 'active') ? 'active' : 'inactive')
                        : 'N/A';
                    $buttonClass = $status === 'active' ? 'btn-success' : ($status === 'inactive' ? 'btn-danger' : 'btn-secondary');
                    $productStatus = $status !== 'N/A'
                        ? '<button type="button" class="btn btn-sm ' . $buttonClass . ' toggle-status-btn" data-id="' . $product->id . '" data-status="' . $status . '">' . $status . '</button>'
                        : 'N/A';
                    $viewBtn = '<a href="' . route('product.ledger', $product->id) . '" class="btn btn-sm btn-success">View</a>';
                    $barcodeBtn = "<a href='#' data-id='{$product->id}' class='btn btn-sm btn-info barcode-print-btn'>Barcode</a>";
                    $editBtn = '';
                    if (Auth::user()->can('products.edit')) {
                        $editBtn = '<a href="' . route('product.edit', $product->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                    }
                    // $deleteBtn = '';
                    // if (Auth::user()->can('products.delete')) {
                    //     $deleteBtn = '<a href="'.route('product.destroy', $product->id).'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>';
                    // }
                    $deleteBtn = Auth::user()->can('products.delete')
                        ? '<a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="confirmDelete(' . $product->id . ')">Delete</a>'
                        : '';
                    return   $viewBtn . ' ' . $editBtn . ' ' . $deleteBtn . ' ' . $barcodeBtn . ' ' . $productStatus; // Concatenating the buttons
                })
                ->rawColumns(['image', 'action']) // Allow HTML in 'image' and 'action' columns
                ->make(true);
        }

        return view('pos.products.product.product-show');
    }

    public function productStatus($id)
    {
        $product = Product::findOrFail($id);

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
    public function variationStatus($id)
    {
        $variation = Variation::findOrFail($id);

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
    public function edit($id)
    {
        // dd($id);
        // try{
        //     $product = Product::with('defaultVariationsEdit')->findOrFail($id);
        //     return response()->json([
        //      'product' => $product
        //     ]);
        // }catch(\Exception $e){
        //     return redirect()->back()->with('error', 'Something went wrong');
        // }
        $product = Product::with('defaultVariationsEdit')->findOrFail($id);
        return view('pos.products.product.product-edit', compact('product'));
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
        return back()->with('message', "Product deleted successfully");
        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Product deleted successfully',
        // ]);
    }
    catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'An error occurred while deleting the product.',
            'error' => $e->getMessage(),
        ]);
    }
    }

    // product find
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

    // product Barcode
    public function variantBarcode(Request $request, $id)
    {
        $variant = Variation::where('barcode', $id)->first();
        $quantity = $request->query('quantity', 1); // Get the quantity from the query parameter, default to 1

        return view('pos.products.product.product-barcode', compact('variant', 'quantity'));
    }
    // public function globalSearch($search_value)
    // {
    //     $product = Product::where('search_value');
    //     $posSetting = PosSetting::first();
    //     // $variant = Variation::where('search_value');
    //     // if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
    //     // $products = Product::withSum('stockQuantity', 'stock_quantity')->where('name', 'LIKE', '%' . $search_value . '%')
    //     //     // ->orWhere('details', 'LIKE', '%' . $search_value . '%')
    //     //     // ->orWhere('price', 'LIKE', '%' . $search_value . '%')
    //     //     ->orWhereHas('category', function ($query) use ($search_value) {
    //     //         $query->where('name', 'LIKE', '%' . $search_value . '%');
    //     //     })
    //     //     ->orWhereHas('subcategory', function ($query) use ($search_value) {
    //     //         $query->where('name', 'LIKE', '%' . $search_value . '%');
    //     //     })
    //     //     ->orWhereHas('brand', function ($query) use ($search_value) {
    //     //         $query->where('name', 'LIKE', '%' . $search_value . '%');
    //     //     })
    //     //     ->get();

    //     //     } else {
    //     //      $products = Product::where('branch_id', Auth::user()->branch_id)
    //     //         ->withSum('stockQuantity', 'stock_quantity')
    //     //         ->where('name', 'LIKE', '%' . $search_value . '%')
    //     //         // ->orWhere('details', 'LIKE', '%' . $search_value . '%')
    //     //         // ->orWhere('price', 'LIKE', '%' . $search_value . '%')
    //     //         ->orWhereHas('category', function ($query) use ($search_value) {
    //     //             $query->where('name', 'LIKE', '%' . $search_value . '%');
    //     //         })
    //     //         ->orWhereHas('subcategory', function ($query) use ($search_value) {
    //     //             $query->where('name', 'LIKE', '%' . $search_value . '%');
    //     //         })
    //     //         ->orWhereHas('brand', function ($query) use ($search_value) {
    //     //             $query->where('name', 'LIKE', '%' . $search_value . '%');
    //     //         })
    //     //         ->get();
    //     // }
    //     if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {

    //         $products = Product::withSum('stockQuantity', 'stock_quantity')
    //             ->with(['variations' => function ($query) {
    //                 $query->select('id', 'product_id', 'b2b_price', 'b2c_price') // Include both prices
    //                     ->where('status', 'default')
    //                     ->get(); // Use limit(1) instead of first() in eager loading
    //             }])
    //             ->where('name', 'LIKE', '%' . $search_value . '%')
    //             ->orWhereHas('category', function ($query) use ($search_value) {
    //                 $query->where('name', 'LIKE', '%' . $search_value . '%');
    //             })
    //             ->orWhereHas('subcategory', function ($query) use ($search_value) {
    //                 $query->where('name', 'LIKE', '%' . $search_value . '%');
    //             })
    //             ->orWhereHas('brand', function ($query) use ($search_value) {
    //                 $query->where('name', 'LIKE', '%' . $search_value . '%');
    //             })
    //             ->get();
    //     } else {
    //         $products = Product::where('branch_id', Auth::user()->branch_id)
    //             ->withSum('stockQuantity', 'stock_quantity')
    //             ->with(['variations' => function ($query) {
    //                 $query->select('id', 'product_id', 'b2b_price', 'b2c_price') // Include both prices
    //                     ->where('status', 'default')
    //                     ->get(); // Use limit(1) instead of first()
    //             }])
    //             ->where('name', 'LIKE', '%' . $search_value . '%')
    //             ->orWhereHas('category', function ($query) use ($search_value) {
    //                 $query->where('name', 'LIKE', '%' . $search_value . '%');
    //             })
    //             ->orWhereHas('subcategory', function ($query) use ($search_value) {
    //                 $query->where('name', 'LIKE', '%' . $search_value . '%');
    //             })
    //             ->orWhereHas('brand', function ($query) use ($search_value) {
    //                 $query->where('name', 'LIKE', '%' . $search_value . '%');
    //             })
    //             ->get();
    //     }
    //     return response()->json([
    //         'products' => $products,
    //         'pos_setting' => $posSetting,
    //         'status' => 200
    //     ]);
    // }


    // public function globalSearch($search_value)
    // {
    //     // Split the search value into individual words
    //     $search_terms = array_filter(explode(' ', trim($search_value)));

    //     // Fetch POS settings
    //     $posSetting = PosSetting::first();

    //     // Base query for products
    //     $query = Product::withSum('stockQuantity', 'stock_quantity')
    //         ->with(['variations' => function ($query) {
    //             $query->select('id', 'product_id', 'b2b_price', 'b2c_price')
    //                 ->where('status', 'default');
    //         }]);

    //     // Apply branch filter for non-admin users
    //     if (Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'admin') {
    //         $query->where('branch_id', Auth::user()->branch_id);
    //     }

    //     // Build search conditions for product name
    //     $query->where(function ($q) use ($search_terms) {
    //         foreach ($search_terms as $term) {
    //             $q->where('name', 'LIKE', '%' . $term . '%');
    //         }
    //     });

    //     // Search in category, subcategory, and brand
    //     $query->orWhere(function ($q) use ($search_terms) {
    //         $q->whereHas('category', function ($query) use ($search_terms) {
    //             foreach ($search_terms as $term) {
    //                 $query->where('name', 'LIKE', '%' . $term . '%');
    //             }
    //         })
    //             ->orWhereHas('subcategory', function ($query) use ($search_terms) {
    //                 foreach ($search_terms as $term) {
    //                     $query->where('name', 'LIKE', '%' . $term . '%');
    //                 }
    //             })
    //             ->orWhereHas('brand', function ($query) use ($search_terms) {
    //                 foreach ($search_terms as $term) {
    //                     $query->where('name', 'LIKE', '%' . $term . '%');
    //                 }
    //             });
    //     });

    //     // Execute the query
    //     $products = $query->get();

    //     // Return JSON response
    //     return response()->json([
    //         'products' => $products,
    //         'pos_setting' => $posSetting,
    //         'status' => 200
    //     ]);
    // }



    public function globalSearch(Request $request,$search_value)
    {
        $search_terms = array_filter(explode(' ', trim($search_value)));
        $customer_id = $request->customerId;
        $posSetting = PosSetting::first();

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

        return view('pos.products.product-ledger.product-ledger', compact('data', 'reports', 'variations'));
    }
    public function latestProduct()
    {
        $product = Product::latest()->first(); // Fetch the latest product
        return response()->json([
            'product' => $product, // Return as 'product', not 'products'
            'status' => 200
        ]);
    }
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
        // dd( $sizes);
        return response()->json([
            'sizes' => $sizes,
            'status' => 200
        ]);
    }
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
                    'branch_id' => Auth::user()->branch_id,
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
    public function productVariationView($id)
    {
        $product = Product::with('productvariation')->findOrFail($id);
        $defaultVariation = $product->productvariation->where('status', 'default')->first();
        return view('pos.products.variations.variation_view', compact('product', 'defaultVariation'));
    }


    // bulk variant view
    public function bulkVariationView()
    {
        return view('pos.products.variation.bulk_variation_view');
    }

    //bulk variant get data
    public function bulkVariationData()
    {
        $product_variations = Variation::with('product', 'stocks', 'variationSize')->orderBy('product_id')->get();
        return response()->json([
            'product_variations' => $product_variations,
            'status' => 200
        ]);
    }


    //bulk vaiant save
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

    // find variants
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
                        'branch_id' => Auth::user()->branch_id,
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
    public function editProductSize(Request $request, $id)
    {
        $product = Product::where('id', $id)->first();
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

        // Pass the variants data to the view
        return view('pos.products.product.product-barcode-all', compact('variants'));
    }

    public function colorAdd(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255|unique:colors,name', // Adjusted unique rule for 'colors' table
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->errors()
                ]);
            }

            // Create new color
            $color = new Color;
            $color->name = $request->input('name');
            $color->save();

            return response()->json([
                'status' => 200,
                'message' => 'Color added successfully',
                'data' => $color
            ]);
        } catch (Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while adding the color',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function colorView()
    {
        try {
            $colors = Color::latest()->get();
            return response()->json([
                'status' => 200,
                'colors' => $colors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching colors',
                'error' => $e->getMessage()
            ]);
        }
    }
}
