<?php

namespace App\Http\Controllers\Api\Products\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
// use Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;
use App\Repositories\RepositoryInterfaces\CategoryInterface;
class ApiCategoryController extends Controller
{

 private $CategoryRepo;
    public function __construct(CategoryInterface $CategoryRepo)
    {
        $this->CategoryRepo = $CategoryRepo;
    }



    public function index()
    {
        return view('pos.products.category');
    }
    public function store(Request $request)
    {
        // dd($request->all());
         try{
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            $category = new Category;
            if ($request->image) {
                $imageName = rand() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/category/'), $imageName);
                $category->image = $imageName;
            }
            $category->name =  $request->name;
            $category->slug = Str::slug($request->name);
            $category->status = 1;
            $category->save();
            return response()->json([
                'status' => 200,
                'message' => 'Category Save Successfully',
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
            'message' => 'An error occurred while adding the category',
            'error' => $e->getMessage()
        ]);
    }


    }
    public function view()
    {
        try{
        $categories = $this->CategoryRepo->getAllCategory();
        // $categories = Category::all();
        return response()->json([
            "status" => 200,
            "data" => $categories
        ]);
    }
    catch(Exception $e){
        return response()->json([
            'status' => 500,
            'message' => 'An error occurred while fetching the categories',
            'error' => $e->getMessage()
        ]);
    }
    }
    public function edit($id)
    {
        try{
        $category = Category::findOrFail($id);
        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category
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
            'message' => 'An error occurred while fetching the category',
            'error' => $e->getMessage()
        ]);
    }
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());
        try{
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            $category = Category::findOrFail($id);
            $category->name =  $request->name;
            $category->slug = Str::slug($request->name);
            if ($request->image) {
                $imageName = rand() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/category/'), $imageName);
                if ($category->image) {
                    $previousImagePath = public_path('uploads/category/') . $category->image;
                    if (file_exists($previousImagePath)) {
                        unlink($previousImagePath);
                    }
                }
                $category->image = $imageName;
            }

            $category->save();
            return response()->json([
                'status' => 200,
                'message' => 'Category Update Successfully',
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
            'message' => 'An error occurred while updating the category',
            'error' => $e->getMessage()
        ]);
    }
    }
    public function status($id)
    {
        try{
        $category = Category::findOrFail($id);
        $newStatus = $category->status == 0 ? 1 : 0;
        $category->update([
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
            'message' => 'An error occurred while changing the status',
            'error' => $e->getMessage()
        ]);
    }
    }
    public function destroy($id)
    {
        try{
        $category = Category::findOrFail($id);
        if ($category->image) {
            $previousImagePath = public_path('uploads/category/') . $category->image;
            if (file_exists($previousImagePath)) {
                unlink($previousImagePath);
            }
        }
        $category->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Category Deleted Successfully',
        ]);
    }
catch(Exception $e){
    return response()->json([
        'status' => 500,
        'message' => 'An error occurred while deleting the category',
        'error' => $e->getMessage()
    ]);
}

}
}
