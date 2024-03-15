<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductSubCategoryController extends Controller
{
    public function index (Request $request)
    {
        SubCategry::where('category_id', $request->category_id)
        ->orderBy('name', 'ASC')->get();

        return response()->json([
            'status' => true,
            'subCategories' => $subCategories
        ]);
    }
}
