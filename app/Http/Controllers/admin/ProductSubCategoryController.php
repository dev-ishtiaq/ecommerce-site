<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductSubCategoryController extends Controller
{
    public function index (Request $request)
    {
        if(!empty($request->category_id)){
            SubCategory::where('category_id', $request->category_id)
            ->orderBy('name', 'ASC')->get();

            return response()->json([
                'status' => true,
                'subCategories' => $subCategories
            ]);
        }

    }
}
