<?php

namespace App\Http\Controllers\admin;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function create ()
    {
        $categories = Category::orderBy('name', 'ASE')->get();
        return view('admin.sub-category.create', compact('categories'));
    }
}
