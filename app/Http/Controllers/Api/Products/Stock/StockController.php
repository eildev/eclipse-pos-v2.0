<?php

namespace App\Http\Controllers\Api\Products\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class StockController extends Controller
{
        public function index()
        {
            try{
            $products = Product::get();
            return view('pos.products.stock.index', compact('products'));
            }
            catch(\Exception $e){
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }

    public function store(Request $request)
    {

        try{
        $validator = Validator::make($request->all(), [
            'stock_quantity' => 'required|max:255',
            'product_id' => 'required|integer',
        ]);

        if ($validator->passes()) {

            Stock::create([
                'branch_id' => Auth::user()->branch_id??$request->branch_id,
                'stock_quantity' => $request->stock_quantity,
                'product_id' => $request->product_id,
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Stock Saved Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages()
            ]);
        } //
    }
    catch(\Exception $e){
        return response()->json([
            'status' => 400,
            'message' => $e->getMessage()
        ]);
    }
    }
    //
    public function view()
    {
        try{
        $stocks = Stock::with('product')->latest()->get();

        return response()->json([
            "status" => 200,
            "data" => $stocks,
        ]);
    }
    catch(\Exception $e){
        return response()->json([
            'status' => 400,
            'message' => $e->getMessage()
        ]);
    }
    } //
    public function edit($id)
    {
        try{
        $stock = Stock::findOrFail($id);

        return response()->json([
            "status" => 200,
            "data" => $stock,
        ]);
    }
    catch(\Exception $e){
        return response()->json([
            'status' => 400,
            'message' => $e->getMessage()
        ]);
    }
    } //
    public function update(Request $request, $id)
    {
        // dd($request->all());
        try{
        $validator = Validator::make($request->all(), [
            'stock_quantity' => 'required|max:255',
            'product_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 500,
                'error' => $validator->messages()
            ]);
        }

        // Find the stock record by ID
        $stock = Stock::find($id);

        if (!$stock) {
            return response()->json([
                'status' => 404,
                'message' => 'Stock not found',
            ]);
        }

        // Update the stock record
        $stock->update([
            'stock_quantity' => $request->stock_quantity,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Stock updated successfully',
        ]);
    }
    catch(\Exception $e){
        return response()->json([
            'status' => 400,
            'message' => $e->getMessage()
        ]);
    }
    }

    public function destroy($id)
    {
        try{
        $stock = Stock::findOrFail($id);
        $stock->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Stock Deleted Successfully',
        ]);
    } //
    catch(\Exception $e){
    return response()->json([
        'status' => 400,
        'message' => $e->getMessage()
    ]);
}
}

}
