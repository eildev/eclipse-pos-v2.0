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

use Exception;

use App\Models\Category;
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




//    main size add

  public function ProductSizeView(){
    try{
        $productSize = Psize::latest()->get();
        // return view('pos.products.product-size.all_products_size',compact('productSize'));
        return response()->json([
            'productSize' => $productSize,
            'status' => 200
        ]);
    }
    catch(Exception $e){
        return response()->json([
            'error' => $e->getMessage(),
            'status' => 404
        ]);
    }
    }//End Method

    public function ProductSizeAdd(){
        try{
        $productSize = Psize::latest()->get();
        $allCategory = Category::latest()->get();
        // return view('pos.products.product-size.add_products_size',compact('allCategory','productSize'));
        return response()->json([
            'productSize' => $productSize,
            'allCategory' => $allCategory,
            'status' => 200
        ]);
        }
        catch(Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 404
            ]);
        }
    }//End Method

    public function ProductSizeStore(Request $request) {
        try{
        $productSize = new Psize;
        $productSize->category_id = $request->category_id;
        $productSize->size = $request->size;
        $productSize->save();
        $notification = [
            'message' => 'Product Size Added Successfully',
            'alert-type' => 'info'
        ];

        return response()->json([
            'message' => $notification['message'],
           'redirect_url' => route('product.size.view')
        ]);
    }
    catch(Exception $e){
        return response()->json([
            'error' => $e->getMessage(),
            'status' => 404
        ]);
    }
    }

    public function ProductSizeEdit($id){
        try{
        $productSize = Psize::findOrFail($id);
        $allCategory = Category::latest()->get();
        // return view('pos.products.product-size.edit_products_size',compact('productSize','allCategory'));
        return response()->json([
            'productSize' => $productSize,
            'allCategory' => $allCategory,
            'status' => 200
        ]);
        }
        catch(Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 404
            ]);
        }
    }//
    public function ProductSizeUpdate(Request $request,$id){
        try{
        $productSize = Psize::findOrFail($id);
        $productSize->category_id = $request->category_id;
        $productSize->size = $request->size;
        $productSize->save();
        // $notification = array(
        //     'message' =>'Product Size Updated Successfully',
        //     'alert-type'=> 'info'
        //  );
        // return redirect()->route('product.size.view')->with($notification);
        return response()->json([
             'status' => 200,
            'message' => 'Product Size Updated Successfully',
            'redirect_url' => route('product.size.view')
        ]);
        }
        catch(Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 404
            ]);
        }
    }//End Method
    public function ProductSizeDelete($id){
        try{
        $productSize = Psize::findOrFail($id);
        $productSize->delete();
        // $notification = array(
        //     'message' =>'Product Size Deleted Successfully',
        //     'alert-type'=> 'info'
        //  );
        // return redirect()->route('product.size.view')->with($notification);
        return response()->json([
             'status' => 200,
            'message' => 'Product Size Deleted Successfully',
            'redirect_url' => route('product.size.view')
        ]);
        }
        catch(Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 404
            ]);
        }
    }//End Meth





}
