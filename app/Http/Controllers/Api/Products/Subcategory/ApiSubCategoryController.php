<?php

namespace App\Http\Controllers\Api\Products\Subcategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Psize;
use App\Models\SubCategory;
use Exception;
use Illuminate\Support\Str;
use App\Repositories\RepositoryInterfaces\SubCategoryInterface;
// use Validator;
use Illuminate\Support\Facades\Validator;
class ApiSubCategoryController extends Controller
{
     private $subCategory;
    public function __construct(SubCategoryInterface $subCategory)
    {
        $this->subCategory = $subCategory;
    }

    public function index()
    {
        try{
        $categories = Category::get();
        // return view('pos.products.category',compact('categories'));
             $html=view('pos.products.subcategory', compact('categories'))->render();
             return response()->json([
                'status' => 200,
                'html' => $html,
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function store(Request $request)
    {

        try{
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category_id' => 'required|max:255',
        ]);

        if ($validator->passes()) {
            $data = $request->only(['name', 'category_id']);

            if ($request->hasFile('image')) {
                $imageName = rand() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/subcategory'), $imageName);
                $data['image'] = $imageName;
            }
            $data['slug'] = Str::slug($request->name);

            $this->subCategory->create($data);
            return response()->json([
                'status' => 200,
                'message' => 'Sub Category Saved Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages()
            ]);
        } //
    }
    catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
    //
    public function view()
    {
        try{
        //   $subcategories = SubCategory::all();
        $subcategories = $this->subCategory->getAllSubCategory();
        $subcategories->load('category');

        return response()->json([
            "status" => 200,
            "data" => $subcategories,

        ]);
    }
    catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    } //
    public function edit($id)
    {
        try{
        //  $category = SubCategory::findOrFail($id);
        $subcategory = $this->subCategory->editData($id);
        // $categories = Category::get();
        if ($subcategory) {
            return response()->json([
                'status' => 200,
                'subcategory' => $subcategory,
                // 'categories' => $categories
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => "Data Not Found"
            ]);
        }
    }
    catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    } //
    public function update(Request $request, $id)
    {

        try{
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category_id' => 'required',
        ]);
        if ($validator->passes()) {
            $subcategory = SubCategory::findOrFail($id);
            $subcategory->name =  $request->name;
            $subcategory->slug = Str::slug($request->name);
            $subcategory->category_id =  $request->category_id;
            if ($request->image) {
                $imageName = rand() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/subcategory'), $imageName);
                if ($subcategory->image) {
                    $previousImagePath = public_path('uploads/subcategory') . $subcategory->image;
                    if (file_exists($previousImagePath)) {
                        unlink($previousImagePath);
                    }
                }
                $subcategory->image = $imageName;
            }

            $subcategory->save();
            return response()->json([
                'status' => 200,
                'message' => 'sub Category Update Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages()
            ]);
        }
    }
    catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    } //
    public function destroy($id)
    {
        try{
        $subcategory = SubCategory::findOrFail($id);
        if ($subcategory->image) {
            $previousImagePath = public_path('uploads/subcategory/') . $subcategory->image;
            if (file_exists($previousImagePath)) {
                unlink($previousImagePath);
            }
        }
        $subcategory->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Sub Category Deleted Successfully',
        ]);
    }
    catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    } //
    public function status($id)
    {

        try{
        $subcategory = SubCategory::findOrFail($id);
        // dd($id);
        $newStatus = $subcategory->status == 0 ? 1 : 0;
        $subcategory->update([
            'status' => $newStatus
        ]);
        return response()->json([
            'status' => 200,
            'newStatus' => $newStatus,
            'message' => 'Status Changed Successfully',
        ]);
    }
    catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function find($id)
    {
        try{
        $subcategory = SubCategory::where('category_id', $id)->where('status',1)->get();
        $size = Psize::where('category_id', $id)->get();
        return response()->json([
            'status' => 200,
            'data' => $subcategory,
            'size' => $size,
        ]);
    }

catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
}
}
