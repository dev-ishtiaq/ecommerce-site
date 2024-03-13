<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\brand;
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

    public function store()
    {

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
