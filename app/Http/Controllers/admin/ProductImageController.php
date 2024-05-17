<?php

namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use App\Http\Controllers\Controller;

use App\Models\ProductImage;
use App\Models\TempImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        // $newName = time().'.'.$ext;
        $sourcePath = $image->getPathName();

        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = 'NULL';
        $productImage->save();

        // added by myself
        // $tempImageInfo = TempImage::find($temp_image_id);
        // $imageName = $tempImageInfo->name;

        $imageName = $request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
        $productImage->image = $imageName;
        $productImage->save();

        // large image
        $destPath = public_path().'/uploads/products/large/'.$imageName;
        // $destPath = public_path().'/uploads/products/large/'.$tempImageInfo->name;

        $manager = new ImageManager(new Driver());

        $img = $manager->read($sourcePath);
        $img->resize(1000, 1200);
        $img->save($destPath);

        // small images
        $destPath = public_path().'/uploads/products/small/'.$imageName;
        // $destPath = public_path().'/uploads/products/small/'.$tempImageInfo->name;
        $img = $manager->read($sourcePath);
        $img->resize(300, 300);
        $img->save($destPath);

        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'imagePath' => asset('uploads/products/small/'.$productImage->image),
            'message' => 'Image saved successfully!',
        ]);
    }
    public function destroy(Request $request)
    {
        $productImage = ProductImage::find($request->id);
        if(empty($productImage)){
            return response()->json([
                'status' => false,
                'message' => 'Image not found!',
            ]);
        }

        File::delete(public_path('uploads/products/small/'.$productImage->image));
        File::delete(public_path('uploads/products/large/'.$productImage->image));

        $productImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully!',
        ]);
    }
}
