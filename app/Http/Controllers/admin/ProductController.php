<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\brand;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create()
    {
        $data = [];

        $categories = Category::orderBy('name', 'ASC')->get();
        $subCategories = SubCategory::orderBy('name', 'ASC')->get();
        $brands = brand::orderBy('name', 'ASC')->get();

        $data['subCategories'] = $subCategories;
        $data['categories'] = $categories;
        $data['brands'] = $brands;

        return view('admin.product.create', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',

        ];

        if(!empty($request->track_qty) && $request->track_qty == 'Yes')
        {
            $rules['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()) {

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function index()
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
