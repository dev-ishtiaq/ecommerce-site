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

        $product = Product::find($request->id)->with('product_images');
        if($product == null) {
            return response()->json([
                'status' => 'false',
                'message' => 'Record not found',
            ]);
        }

        if(Cart::count() > 0) {
            echo 'product already in cart';
            
        } else {
            echo "cart is empty now add a product";
            // cart is empty
            Cart::add($product->id, $product->title, 1, $product->price, [
                'productImage' => (!empty($product->product_images)) ?
                $product->product_images->first() : '']);

            $status = true;
            $message = $product->title." added in cart";
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            ]);
    }

    public function cart() {
        dd(Cart::content());
        return view('frontend.cart');
    }
}
