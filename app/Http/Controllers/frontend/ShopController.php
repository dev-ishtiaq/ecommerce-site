<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\brand;
use App\Models\Product;
class ShopController extends Controller
{
    public function index (Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        // not used, fetching from helper
        $categories = Category::orderBy('name', 'ASC')
                ->with('sub_category')
                ->where('status',1)
                ->get();
        // not used, fetching from helper
                $brands = brand::orderBy('name', 'ASC')
                ->where('status',1)
                ->get();

        // apply filter
        $produccts = Product::where('status', 1)->get();

        // used on shop.blade.php
        $products = Product::orderBy('id', 'DESC')
        ->where('status',1)

                ->get();

        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;


        return view('frontend.shop', $data);
    }
}
