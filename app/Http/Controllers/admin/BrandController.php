<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function create ()
    {
        return view('admin.brand.create');
    }
    public function index () {

    }
    public function store (Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);
    }
}
