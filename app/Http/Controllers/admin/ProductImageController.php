<?php

namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;

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
        $sourcePath = $image->getImagePath();

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

        return resoponse()->json([
            'status' => true,
            'message' => 'Image saved successfully!',
        ]);
    }
}
