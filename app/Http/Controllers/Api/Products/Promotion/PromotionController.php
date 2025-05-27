<?php

namespace App\Http\Controllers\Api\Products\Promotion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Customer;

use App\Models\Promotion;
use App\Models\Product;
use App\Models\PromotionDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
// use Validator;
use Illuminate\Support\Facades\Validator;
class PromotionController extends Controller
{
        public function PromotionAdd()
    {
        try{
            return view('pos.promotion.promotion_add');
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in PromotionAdd',
                'error' => $e->getMessage(),
            ], 500);
        }
        // return view('pos.promotion.promotion_add');
    } //
    public function PromotionStore(Request $request)

    {
          try{
        Promotion::insert([
            'promotion_name' => $request->promotion_name,
            'branch_id' =>  Auth::user()->branch_id??$request->branch_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'description' => $request->description,
            'created_at' => Carbon::now(),
        ]);
        $notification = [
            'message' => 'Promotion Added Successfully',
            'alert-type' => 'info'
        ];
        // return redirect()->route('promotion.view')->with($notification);
        return response()->json([
             'status' => 200,
             'message' => $notification['message'],
             'redirect' => route('promotion.view'),
        ]);
    }
     catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in PromotionStore',
                'error' => $e->getMessage(),
            ], 500);
        }
    } //End Method

    public function PromotionView()
    {
        try{
            // dd(Auth::user());
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $promotions = Promotion::all();
        } else {
            $promotions = Promotion::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        // return view('pos.promotion.promotion_view', compact('promotions'));
        return response()->json([
            'status' => 200,
            'promotions' => $promotions,
        ]);
    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in PromotionView',
                'error' => $e->getMessage(),
            ], 500);
        }
    } //End Method
    public function PromotionEdit($id)
    {
       try{
        $promotion = Promotion::findOrFail($id);
        // return view('pos.promotion.promotion_edit', compact('promotion'));
        return response()->json([
            'status' => 200,
            'promotion' => $promotion,
        ]);
       }
       catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in PromotionEdit',
                'error' => $e->getMessage(),
            ], 500);
        }

    } //End Method
    public function PromotionUpdate(Request $request, $id)
    {
        try{
        $promotion = Promotion::findOrFail($id)->update([
            'promotion_name' => $request->promotion_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'description' => $request->description,
            'updated_at' => Carbon::now(),
        ]);
        $notification = [
            'message' => 'Promotion Updated Successfully',
            'alert-type' => 'info'
        ];
        // return redirect()->route('promotion.view')->with($notification);
        return response()->json([
            'status' => 200,
            'message' => $notification['message'],
            'redirect' => route('promotion.view'),
        ]);
    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in PromotionUpdate',
                'error' => $e->getMessage(),
            ], 500);
        }
    } //End Method
    public function PromotionDelete($id)
    {
        try{
        Promotion::findOrFail($id)->delete();
        $notification = [
            'message' => 'Promotion Deleted Successfully',
            'alert-type' => 'info'
        ];
        // return redirect()->route('promotion.view')->with($notification);
        return response()->json([
            'status' => 200,
            'message' => $notification['message'],
            'redirect' => route('promotion.view'),
        ]);
    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in PromotionDelete',
                'error' => $e->getMessage(),
            ], 500);
        }
    } //End Method
    // find
    public function find($id)
    {
        try{
        $promotion = Promotion::findOrFail($id);
        return response()->json([
            'status' => 200,
            'data' => $promotion,
        ]);
    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in find',
                'error' => $e->getMessage(),
            ], 500);
        }
    } //End Method



    ///////////////////////Start Promotion Details All Method ////////////////////////
    public function PromotionDetailsAdd()
    {
        try{
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $product = Product::latest()->get();
            $promotions = Promotion::latest()->get();
        } else {
            $product = Product::where('branch_id', Auth::user()->branch_id)->latest()->get();
            $promotions = Promotion::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        // return view('pos.promotion.promotion_details_add', compact('product', 'promotions'));
        return response()->json([
            'status' => 200,
            'product' => $product,
            'promotions' => $promotions,
        ]);

    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in PromotionDetailsAdd',
                'error' => $e->getMessage(),
            ], 500);
        }
    } //
    public function PromotionDetailsStore(Request $request)
    {
        // dd($request->all());
        try{
        $validator = Validator::make($request->all(), [
            'promotion_id' => 'required',
            'promotion_type' => 'required',
        ]);

        if ($validator->passes()) {
            // dd($request->all());
            // dd(Auth::user());
            $promotionalDetails =  new PromotionDetails;
            $promotionalDetails->branch_id =  Auth::user()->branch_id;
            $promotionalDetails->promotion_id = $request->promotion_id;
            $promotionalDetails->promotion_type = $request->promotion_type;
            $promotionalDetails->logic = $request->logic;
            $promotionalDetails->additional_conditions = $request->additional_conditions;
            $promotionalDetails->created_at =  Carbon::now();
            $promotionalDetails->save();

            return response()->json([
                'status' => 200,
                'message' => 'Promotion Details Added Successfully'
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'errors' => $validator->errors()
            ]);
        }
    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in PromotionDetailsStore',
                'error' => $e->getMessage(),
            ], 500);
        }
    } //End Method
    public function PromotionDetailsView()
    {
        try{
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $promotion_details = PromotionDetails::all();
        } else {
            $promotion_details = PromotionDetails::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        // return view('pos.promotion.promotion_details_view', compact('promotion_details'));
        return response()->json([
            'status' => 200,
            'promotion_details' => $promotion_details,
        ]);
    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in PromotionDetailsView',
                'error' => $e->getMessage(),
            ], 500);
        }
    } //End Method
    public function PromotionDetailsEdit($id)
    {
        // $product = Product::latest()->get();
        try{
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $promotions = Promotion::latest()->get();
        } else {
            $promotions = Promotion::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        $promotion_details = PromotionDetails::findOrFail($id);
        // return view('pos.promotion.promotion_details_edit', compact('promotion_details', 'promotions'));
        return response()->json([
            'status' => 200,
            'promotion_details' => $promotion_details,
            'promotions' => $promotions,
        ]);
    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in PromotionDetailsEdit',
                'error' => $e->getMessage(),
            ], 500);
        }
    } //End Method

    public function PromotionDetailsUpdate(Request $request, $id)
    {
        try{
        $validator = Validator::make($request->all(), [
            'promotion_id' => 'required',
            'promotion_type' => 'required',
        ]);

        if ($validator->passes()) {
            // dd($request->all());
            $promotionalDetails =  PromotionDetails::findOrFail($id);
            $promotionalDetails->promotion_id = $request->promotion_id;
            $promotionalDetails->promotion_type = $request->promotion_type;
            $promotionalDetails->logic = $request->logic;
            $promotionalDetails->additional_conditions = $request->additional_conditions;
            $promotionalDetails->created_at =  Carbon::now();
            $promotionalDetails->save();

            return response()->json([
                'status' => 200,
                'message' => 'Promotion Details Added Successfully'
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'errors' => $validator->errors()
            ]);
        }
    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in PromotionDetailsUpdate',
                'error' => $e->getMessage(),
            ], 500);
        }
    } //
    public function PromotionDetailsDelete($id)
    {
        try{
        PromotionDetails::findOrFail($id)->delete();
        $notification = [
            'message' => 'Promotion Details Deleted Successfully',
            'alert-type' => 'info'
        ];
        // return redirect()->route('promotion.details.view')->with($notification);
        return response()->json([
            'status' => 200,
            'message' => 'Promotion Details Deleted Successfully',
        ]);
    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in PromotionDetailsDelete',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function PromotionDetailsFind(Request $request)
    {
        try{
        $type = $request->type;

        if ($type) {
            if ($type == 'wholesale') {
                // $wholesale = Product::where('branch_id', Auth::user()->branch_id)->where('stock', ">", 0)->get();
                $wholesale = Product::withSum('stockQuantity', 'stock_quantity')
                    ->having('stock_quantity_sum_stock_quantity', '>', 0)
                    ->orderBy('stock_quantity_sum_stock_quantity', 'asc') // or 'desc'
                    ->get();

                return response()->json([
                    "status" => 200,
                    'wholesale' => $wholesale
                ]);
            } else if ($type == 'products') {
                $products = Product::withSum('stockQuantity', 'stock_quantity')
                    ->having('stock_quantity_sum_stock_quantity', '>', 0)
                    ->orderBy('stock_quantity_sum_stock_quantity', 'asc') // or 'desc'
                    ->get();
                return response()->json([
                    "status" => 200,
                    'products' => $products
                ]);
            } else if ($type == 'customers') {
                $customers = Customer::where('party_type', 'customer')->where('branch_id', Auth::user()->branch_id)->get();
                return response()->json([
                    "status" => 200,
                    'customers' => $customers
                ]);
            } else if ($type == 'branch') {
                $branch = Branch::get();
                return response()->json([
                    "status" => 200,
                    'branch' => $branch
                ]);
            } else {
                return response()->json([
                    "status" => 200,
                    'message' => "Data not found",
                ]);
            }
        } else {
            return response()->json([
                "status" => 500,
                'message' => "Data not found",
            ]);
        }
    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in PromotionDetailsFind',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function allProduct()
    {
        try{
            // dd(Auth::user()->branch_id);
        $products = Product::withSum('stockQuantity', 'stock_quantity')
            ->having('stock_quantity_sum_stock_quantity', '>', 0)
            ->orderBy('stock_quantity_sum_stock_quantity', 'asc') // or 'desc'
            ->get();

        return response()->json([
            "status" => 200,
            'products' => $products
        ]);
    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in allProduct',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function allCustomers()
    {
        try{
        $customers = Customer::where('party_type', 'customer')->where('branch_id', Auth::user()->branch_id)->get();
        return response()->json([
            "status" => 200,
            'customers' => $customers
        ]);
    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in allCustomers',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function allBranch()
    {
        try{
        $branch = Branch::get();
        return response()->json([
            "status" => 200,
            'branch' => $branch
        ]);
    }
    catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in allBranch',
                'error' => $e->getMessage(),
            ], 500);
        }
}

}
