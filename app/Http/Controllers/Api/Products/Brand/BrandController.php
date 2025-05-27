<?php

namespace App\Http\Controllers\Api\Products\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;

use Illuminate\Support\Str;
use App\Repositories\RepositoryInterfaces\BrandInterface;
use Illuminate\Support\Facades\Validator;
class BrandController extends Controller
{
   private $brandRepo;
    public function __construct(BrandInterface $brandRepos){
        $this->brandRepo = $brandRepos;
    }
    public function index()
    {
        try{

            // return view('pos.products.brand');
            $html=view('pos.products.brand')->render();
            return response()->json([
                'status' => 200,
                'html' => $html
            ]);
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
            'name' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            $brand = new Brand;
            if ($request->image) {
                $imageName = rand() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/brand/'), $imageName);
                $brand->image = $imageName;
            }
            $brand->name =  $request->name;
            $brand->slug = Str::slug($request->name);
            $brand->description = $request->description;
            $brand->save();
            return response()->json([
                'status' => 200,
                'message' => 'Brand Save Successfully',
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
        // $brands = Brand::all();
        $brands = $this->brandRepo->getAllBrand();
        // dd($brands);
        return response()->json([
            "status" => 200,
            "data" => $brands
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
        $brand = $this->brandRepo->editData($id);
        if ($brand) {
            return response()->json([
                'status' => 200,
                'brand' => $brand
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
        try{
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            $brand = Brand::findOrFail($id);
            $brand->name =  $request->name;
            $brand->slug = Str::slug($request->name);
            $brand->description = $request->description;
            if ($request->image) {
                $imageName = rand() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/brand/'), $imageName);
                if ($brand->image) {
                    $previousImagePath = public_path('uploads/brand/') . $brand->image;
                    if (file_exists($previousImagePath)) {
                        unlink($previousImagePath);
                    }
                }
                $brand->image = $imageName;
            }

            $brand->save();
            return response()->json([
                'status' => 200,
                'message' => 'Brand Update Successfully',
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
        $brand = Brand::findOrFail($id);
        if ($brand->image) {
            $previousImagePath = public_path('uploads/brand/') . $brand->image;
            if (file_exists($previousImagePath)) {
                unlink($previousImagePath);
            }
        }
        $brand->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Brand Deleted Successfully',
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


