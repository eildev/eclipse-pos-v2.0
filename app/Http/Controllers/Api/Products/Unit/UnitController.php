<?php

namespace App\Http\Controllers\Api\Products\Unit;

use App\Http\Controllers\Controller;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class UnitController extends Controller
{
        public function index()
    {
        try{
        return view('pos.products.unit');
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
        // dd($request->all());
        try{
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:39',
            'related_to_unit' => 'required|max:39',
            'related_sign' => 'required|max:19',
            'related_by' => 'required|max:10',
        ]);

        if ($validator->passes()) {
            $unit = new Unit;
            $unit->name =  $request->name;
            $unit->related_to_unit = $request->related_to_unit;
            $unit->related_sign = $request->related_sign;
            $unit->related_by = $request->related_by;
            $unit->save();
            return response()->json([
                'status' => 200,
                'message' => 'Unit Save Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages()
            ]);
        }
    }
    catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function view()
    {
        try{
        $units = Unit::get();
        return response()->json([
            "status" => 200,
            "data" => $units
        ]);
    }
    catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function edit($id)
    {
        try{
        $unit = Unit::findOrFail($id);
        if ($unit) {
            return response()->json([
                'status' => 200,
                'unit' => $unit
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => "Data Not Found"
            ]);
        }
    }
    catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());
        try{
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:39',
            'related_to_unit' => 'required|max:39',
            'related_sign' => 'required|max:19',
            'related_by' => 'required|max:10',
        ]);
        if ($validator->passes()) {
            $unit = Unit::findOrFail($id);
            $unit->name =  $request->name;
            $unit->related_to_unit = $request->related_to_unit;
            $unit->related_sign = $request->related_sign;
            $unit->related_by = $request->related_by;
            $unit->save();
            return response()->json([
                'status' => 200,
                'message' => 'Unit Update Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages()
            ]);
        }
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
        $unit = Unit::findOrFail($id);
        $unit->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Unit Deleted Successfully',
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
