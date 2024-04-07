<?php

namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
// use Intervention\Image\Drivers\Imagick\Driver;


class TempImagesController extends Controller
{
   public function create (Request $request)
   {
      $image = $request->image;
      if(!empty($image)) {
        $ext = $image->getClientOriginalExtension();
        $newName = time().'.'.$ext;

        $tempImage = new TempImage();

        $tempImage->name = $newName;
        $tempImage->save();

        $image->move(public_path().'/tempImage', $newName);


        // Generate thumbnail
        $sourcePath = public_path().'/tempImage/'.$newName;
        $destPath = public_path().'/tempImage/thumb/'.$newName;

        $manager = new ImageManager(new Driver());

        $img = $manager->read($sourcePath);
        $img->resize(300, 250);

        $img->save($destPath);

        $img = $request->image;

        // ->toJpeg(80)

        return response()->json([
            'status' => true,
            'image_id' => $tempImage->id,
            'imagePath' => asset('/tempImage/thumb/'.$newName),
            'message' => 'image uploaded successfully'
        ]);


      }
   }
}
