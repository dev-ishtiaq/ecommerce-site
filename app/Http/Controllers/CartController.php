<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request) {
        // Cart::add('293ad','Product 1', 1, 9.00);

        $product = Product::with('product_images')->find($request->id);
        if($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found',
            ]);
        }

        if(Cart::count() > 0) {
            // echo 'product already in cart';
            // found in cart
            // check if this product already in cart
            // if product not found in cart

            $cartContent = Cart::content();
            $productAlreadyeExist = false;
            foreach ($cartContent as $item) {
                if($item->id == $product->id){
                    $productAlreadyeExist = true;
                }
                if ($productAlreadyeExist == false) {
                    Cart::add($product->id, $product->title, 1, $product->price, [
                        'productImage' => (!empty($product->product_images)) ?
                        $product->product_images->first() : '']);

                        $status = true;
                        $message = $product->title." added in cart";
                }
                else {
                    $status = false;
                    $message = $product->title." already added in cart";
                }
            }

        } else {
            // echo "cart is empty now add a product";
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
        // dd(Cart::content());

        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;
        return view('frontend.cart', $data);
    }
}
