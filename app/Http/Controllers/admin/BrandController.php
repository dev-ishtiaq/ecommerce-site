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

        if(!empty($request->get('keyword'))){
            $brands = $brands->where('name', 'like', '%' .$request->get('keyword').'%' );
        }

        $brands = $brands->paginate(10);

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
    public function edit (Request $request, $id) {
        $brand = Brand::find($id);
        if(empty($brand))
        {
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('brand.index');
        }


        $data['brand'] = $brand;
        return view('admin.brand.edit', $data);
    }

// <<<<<<<<<<<<<   UPDATE >>>>>>>>>>>>>>
    public function update (Request $request, $id) {
        $brand = Brand::find($id);
        if(empty($brand))
        {
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => false,
                'notFound'=> true,
            ]);
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',

        ]);

        if($validator->passes())
        {

            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success', 'brand edited successfully!');
            return response()->json([
                'status' => true,
                'message' => 'brand edited successfully!',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy (Request $request, $id) {
        $brand = Brand::find($id);

        if(empty($brand))
        {
         $request->session()->flash('error', 'Brand Not found');
        return response()->json([
            'status' => true,
            'message' => 'Brand Not found'
        ]);

        }
        $brand->delete();
        $request->session()->flash('success', 'Brand Deleted Successfully');

        return response()->json([
            'status' => true,
            'message' => 'Brand Deleted Successfully'
        ]);
    }
}
