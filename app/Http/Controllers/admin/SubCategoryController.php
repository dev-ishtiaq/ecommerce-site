<?php

namespace App\Http\Controllers\admin;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function create ()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub-category.create', compact('categories'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required'
        ]);
        if ($validator->passes()) {

        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
