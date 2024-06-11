<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart() {

    }

    public function cart() {
        return view('frontend.cart');
    }
}
