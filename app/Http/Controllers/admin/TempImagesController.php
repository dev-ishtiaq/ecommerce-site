<?php

namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TempImage;

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
        $image->move(public_path(). '/tempImage', $newName);

        return response()->json([
            'status' => true,
            'image_id' => $tempImage->id,
            'message' => 'image uploaded successfully'
        ]);


      }
   }
}
