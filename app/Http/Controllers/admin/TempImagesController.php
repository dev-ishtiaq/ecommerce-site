<?php

namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class TempImagesController extends Controller
{
   public function create (Request $request)
   {
      $image = $request->file('image');
      if(!empty($image)) {
        $ext = $image->getClientOriginalExtension();
        $newName = time().'.'.$ext;

        $tempImage = new TempImage();

        $tempImage->name = $newName;
        $tempImage->save();

        $image->move(public_path().'/tempImage', $newName);

        // Generate thumbnail

        $manager = new ImageManager(new Driver());

        $sourcePath = public_path().'/tempImage/'.$newName;
        $destPath = public_path().'/tempImage/thumb/'.$newName;

        $img = $manager->read($sourcePath);
        $img->resize(300, 280);
        $img->save($destPath);

        // ->toJpeg(80)
        // $image = $request->file('image');

        // $sourcePath = public_path().'/tempImage/'.$newName;
        // $destPath = public_path().'/tempImage/thumb/'.$newName;
        // $image = $manager::make($sourcePath);
        // $image->fit(300, 280);

        // $image->save($destPath);

        return response()->json([
            'status' => true,
            'image_id' => $tempImage->id,
            'imagePath' => asset('/tempImage/thumb/'.$newName),
            'message' => 'image uploaded successfully'
        ]);


      }
   }
}
