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

        if(!empty($request->get('brand'))){
            $brandsArray = explode(',',$request->get('brand'));
            $products = $products->whereIn('brand_id', $brandsArray);
        }
        if($request->get('price_max') != '' && $request->get('price_min') != ''){
            if($request->get('price_max') ==  1000) {

                $products = $products->whereBetween('price', [intval($request->get('price_min')), 10000]);
            }
            else {
                $products = $products->whereBetween('price', [intval($request->get('price_min')),intval
                ($request->get('price_max'))]);
            }
        }

        if($request->get('sort') != '') {
            if($request->get('sort') == 'latest') {
                $products = $products->orderBy('id', 'DESC');
            } else if ($request->get('sort') == 'price_asc') {
                $products = $products->orderBy('price', 'ASC');
            } else {
                $products = $products->orderBy('price', 'DESC');
            }
        } else {
            $products = $products->orderBy('id', 'DESC');
        }
        $products = $products->paginate(6);
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
        $data['priceMax'] = (intval($request->get('price_max')) == 0) ? 1000 : $request->get('price_max');
        $data['priceMin'] = intval($request->get('price_min'));
        $data['sort'] = ($request->get('sort'));


        return view('frontend.shop', $data);
    }
    public function product($slug) {
        // echo $slug;
        $product = Product::where('slug',$slug)->with('product_images')->first();
        if($product == null) {
            abort(402);
        }
        $relatedProducts = [];
        // fetch related products
        if {$product->related_products != ''} {
          $productArray = explode(',', $product->related_products);
        }


        $data['product'] = $product;
        return view('frontend.product', $data);
    }
}
