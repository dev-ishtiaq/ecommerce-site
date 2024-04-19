<?php
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
// use Intervention\Image\Drivers\Imagick\Driver;
class ProductController extends Controller
{   public function index()
    {
        $products = Product::latest('id')->with('product_images')->paginate(10);
        // dd($products);
        $data['products'] =  $products;
        return view('admin.product.list', compact('products'));


        if(!empty($request->get('keyword'))){
            $products = $products->where('name', 'like', '%' .$request->get('keyword').'%' );
        }


    }

    public function create()
    {
        $data = [];

        $categories = Category::orderBy('name', 'ASC')->get();
        $subCategories = SubCategory::orderBy('name', 'ASC')->get();
        $brands = brand::orderBy('name', 'ASC')->get();

        $data['categories'] = $categories;
        $data['subCategories'] = $subCategories;
        $data['brands'] = $brands;

        return view('admin.product.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->image_array);
        // exit();
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',

        ];

        if(!empty($request->track_qty) && $request->track_qty == 'Yes')
        {
            $rules['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()) {
            $product = new Product;
            $product->title         = $request->title;
            $product->slug          = $request->slug;
            $product->description   = $request->description;
            $product->price         = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku           = $request->sku;
            $product->barcode       = $request->barcode;
            $product->track_qty     = $request->track_qty;
            $product->qty           = $request->qty;
            $product->status        = $request->status;
            $product->category_id   = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id      = $request->brand;
            $product->is_featured   = $request->is_featured;
            $product->save();






            // save gallery image
            if(!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.',$tempImageInfo->name);
                    $ext = last($extArray);

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';

                    $productImage->save();

                    // $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $imageName = $tempImageInfo->name;
                    $productImage->image = $imageName;
                    $productImage->save();

        // ==========  generate product thumbnail ==========

        //==== large image ======

                    $sourcePath = public_path().'/tempImage/'.$tempImageInfo->name;
                    $destPath = public_path().'/uploads/products/large/'.$tempImageInfo->name;

                    $manager = new ImageManager(new Driver());

                    $img = $manager->read($sourcePath);
                    $img->resize(1400, null, function($constraint) {
                    $constraint->aspectRatio();
                    });
                    $img->save($destPath);

                    // small images
                    $destPath = public_path().'/uploads/products/small/'.$tempImageInfo->name;
                    $img = $manager->read($sourcePath);
                    $img->scale(300, 300);
                    $img->save($destPath);

                }
            }
            $request->session()->flash('success', 'Product added successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Product added successfully!',
            ]);
            } else {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }

    }

    public function edit(Request $request, $id)
    {
        $data =[];
        $categories = Category::orderBy('name', 'ASC')->get();
        $subCategories = SubCategory::orderBy('name', 'ASC')->get();
        $brands = brand::orderBy('name', 'ASC')->get();

        $data['categories'] = $categories;
        $data['subCategories'] = $subCategories;
        $data['brands'] = $brands;

            return view('admin.product.edit', $data);
    }

    public function update()
    {

    }

    public function destroy()
    {


    }
}
