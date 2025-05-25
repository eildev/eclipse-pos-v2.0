<?php

namespace App\Http\Controllers\Api\Products\Color;

use App\Http\Controllers\Controller;
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
class ColorController extends Controller
{
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
