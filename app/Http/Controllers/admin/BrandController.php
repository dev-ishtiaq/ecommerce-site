<?php

namespace App\Http\Controllers\admin;

use App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    public function create ()
    {
        return view('admin.brand.create');
    }
    public function index (Request $request) {
        $brands = Brand::latest('id');
        $brands = $brands->get();

        return view('admin.brand.list', compact('brands'));
    }
    public function store (Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);

        if($validator->passes())
        {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            return response()->json([
                'status' => true,
                'message' => 'brand added successfully!',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit (Request $request) {
        
    }
}
