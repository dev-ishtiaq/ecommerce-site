<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class FrontController extends Controller
{
    public function index()
    {
        $product =       Product::where('is_featured', 'Yes')
                                ->orderBy('id', 'DESC')
                                ->where('status', 1)
                                ->take(4)
                                ->get();

        $data['featuredProducts'] = $product;

        $latestProduct = Product::orderBy('id', 'DESC')
                                ->where('status', 1)
                                ->take(8)
                                ->get();
        $data['latestProduct'] = $latestProduct;

        return view('frontend.home',$data);
        
    }
}
