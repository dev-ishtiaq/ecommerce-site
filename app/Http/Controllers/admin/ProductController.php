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
use Illuminate\Support\Facades\File;
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
            $product->short_description   = $request->short_description;
            $product->description   = $request->description;
            $product->shipping_returns   = $request->shipping_returns;
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
                    $img->resize(300, 300);
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
    public function  edit(Request $request, $id)
    {
        $product = Product::find($id);
        if(empty($product)){
            // $request->session()->flash('error', 'product not found!');
            return redirect()->route('product.index')->with('error', 'product not found!');
        }

        // fetch product image
        $productImages = ProductImage::where('product_id',$product->id)->get();

        $categories = Category::orderBy('name', 'ASC')->get();
        $SubCategories = SubCategory::where('category_id', $product->category_id)->get();
        $brands = brand::orderBy('name', 'ASC')->get();

        $relatedProducts = [];
        // fetch related products
        if ($product->related_products != '') {
          $productArray = explode(',', $product->related_products);
          $relatedProducts = Product::whereIn('id', $productArray)->with('product_images')->get();
        }

        $data = [];
        $data['product'] = $product;
        $data['productImages'] = $productImages;
        $data['categories'] = $categories;
        $data['SubCategories'] = $SubCategories;
        $data['brands'] = $brands;
        $data['relatedProducts'] = $relatedProducts;

        return view('admin.product.edit', $data);
    }
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',

            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
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
            $product->title         = $request->title;
            $product->slug          = $request->slug;
            $product->short_description   = $request->short_description;
            $product->description   = $request->description;
            $product->shipping_returns   = $request->shipping_returns;
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
            $product->related_products   = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            $product->save();


            $request->session()->flash('success', 'Product updated successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully!',
            ]);
            } else {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }

    }
    public function destroy($id, Request $request)
    {
        $product = Product::find($id);
        if(empty($product)){
            $request->session()->flash('error','product not found!');
            return response()->json([
                'status' => false,
                'notFound' =>true,
            ]);
        }
        $productImages = ProductImage::where('product_id',$id);

        if(!empty($productImages)){
            foreach($productImages as $productImage){
                File::delete(public_path('uploads/products/large'.$productImage->image));
                File::delete(public_path('uploads/products/small'.$productImage->image));
            }
            ProductImage::where('product_id',$id)->delete();

        }
        $product->delete();

        $request->session()->flash('success','product deeleted successfully!');

        return response()->json([
            'status' => true,
            'message' =>'product deleted successfully!',
        ]);
    }
    public function getProducts(Request $request){
        $tempProduct = [];
        if($request->term != "") {
            $products = Product::where('title','like','%'.$request->term.'%')->get();

            if($products != null) {
                foreach ($products as $product) {
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->title);

                }
            }
        }
        return response()->json([
            'tags' => $tempProduct,
            'status' => true,

        ]);
    }
}
