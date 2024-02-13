<?php

namespace App\Http\Controllers\admin;
use App\Models\Category;
use App\Models\SubCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $SubCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
        ->latest('sub_categories.id')->leftJoin('categories', 'categories.id',
        'sub_categories.category_id');

        if(!empty($request->get('keyword'))){
            $SubCategories = $SubCategories->where(
                'sub_categories.name', 'like', '%' .$request->get('keyword').'%' );
                $SubCategories = $SubCategories->orwhere(
                    'sub_categories.name', 'like', '%' .$request->get('keyword').'%' );
        }


        $SubCategories = $SubCategories->paginate(10);
        $data['SubCategories'] =  $SubCategories;
        return view('admin.sub-category.list', compact('SubCategories'));
    }

    public function create ()
    {

        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub-category.create', compact('categories'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required'
        ]);
        if ($validator->passes()) {
            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            $request->session()->flash('success', 'Sub category created successfully!');

            return response([
                'status' => true,
                'message' => 'Sub category created successfully!',
            ]);

        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit (Request $request, $id)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $data  ['categories'] = $categories;
        return view('admin.sub-category.edit', $data);
    }
    public function update (Request $request, $id)
    {
        $subCategory = SubCategory::find($id);
        if(empty($subCategory))
        {
            $request->session()->flash('error', 'record not found');
            return response([
                'status' => false,
                'notFound' => true,

            ]);
        }
    }
    public function destroy ()
    {

    }

}
