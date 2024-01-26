<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest();

        if(!empty($request->get('keyword'))){
            $categories = $categories->where('name', 'like', '%' .$request->get('keyword').'%' );
        }

        $categories = $categories->paginate(10);
        $data['categories'] =  $categories;
        return view('admin.category.list', compact('categories'));
    }
    public function create()
    {
        return view('admin.category.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories',

        ]);

        if($validator->passes()){

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            if(!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'.'.$ext;

                $sPath = public_path().'/tempImage/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath, $dPath);

                // create new manager instance with desired driver
                $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                // $manager = new ImageManager(new Driver());
                $image = ImageManager::make($sPath)->resize(300, 200);
                // $image = ImageManager::create($sPath);

                $image->save($dPath);

                $category->image = $newImageName;
                $category->save();
            }




            $request->session()->flash('success', 'Category Added Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category Added Successfully'
            ]);


        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
