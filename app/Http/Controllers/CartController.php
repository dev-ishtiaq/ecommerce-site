<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request) {
        // Cart::add('293ad','Product 1', 1, 9.00);

        $product = Product::find($request->id);
        if($product == null) {
            return response()->json([
                'status' => 'false',
                'message' => 'Record not found',
            ]);
        }

        if(Cart::count() > 0) {

        } else {
            // cart is empty
            Cart::add($product->id,$product->title, 1, $product->price, ['productImage']);
        }
    }

    public function cart() {
        return view('frontend.cart');
    }
}
