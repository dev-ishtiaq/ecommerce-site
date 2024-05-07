<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\brand;
use App\Models\Product;
class ShopController extends Controller
{
    public function index (Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected = '';
        $subCategorySelected = '';
        $brandsArray = [];
        
        if(!empty($request->get('brand'))){

            $brandsArray = explode(',',$request->get('brand'));
        }

        // not used, fetching from helper
        $categories = Category::orderBy('name', 'ASC')
                ->with('sub_category')
                ->where('status',1)
                ->get();
        // not used, fetching from helper
                $brands = brand::orderBy('name', 'ASC')
                ->where('status',1)
                ->get();

        $products = Product::where('status', 1);
        // apply filter
        if(!empty($categorySlug)){
            $category = Category::where('slug', $categorySlug)->first();
            $products = $products->where('category_id', $category->id);
            $categorySelected = $category->id;
        }
        if(!empty($subCategorySlug)){
            $SubCategory = subCategory::where('slug', $subCategorySlug)->first();
            $products = $products->where('sub_category_id', $SubCategory->id);
            $subCategorySelected = $SubCategory->id;
        }

        $products = $products->orderBy('id', 'DESC');
        $products = $products->get();
        // used on shop.blade.php
        // $products = Product::orderBy('id', 'DESC')
        // ->where('status',1)
        //         ->get();

        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['brandsArray'] = $brandsArray;


        return view('frontend.shop', $data);
    }
}
