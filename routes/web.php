<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\ProductImageController;

use App\Http\Controllers\frontend\FrontController;
use App\Http\Controllers\frontend\ShopController;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [FrontController:: class, 'index'])->name('home.index');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController:: class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ShopController:: class, 'product'])->name('front.product');
Route::get('/cart', [CartController:: class, 'cart'])->name('front.cart');
Route::post('/add-to-cart', [CartController:: class, 'addToCart'])->name('front.addToCart');
Route::post('/update-cart', [CartController:: class, 'updateCart'])->name('front.updateCart');


Route::group(['prefix' => 'admin'], function()
{
    Route::group(['middleware' => 'admin.guest'], function()
    {
        Route::get('/login',[AdminLoginController:: class, 'index'])->name('admin.login');
        Route::post('/authenticate',[AdminLoginController:: class, 'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function()
    {
        Route::get('/dashboard',[HomeController:: class, 'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController:: class, 'logout'])->name('admin.logout');

        // category routes
        Route::get('/categories/create',[CategoryController:: class, 'create'])->name('categories.create');
        Route::get('/categories',[CategoryController:: class, 'index'])->name('categories.index');
        Route::post('/categories',[CategoryController:: class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit',[CategoryController:: class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}',[CategoryController:: class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}',[CategoryController:: class, 'destroy'])->name('categories.destroy');

        // sub category routes
        Route::get('/sub-categories',[SubCategoryController:: class, 'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create',[SubCategoryController:: class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories',[SubCategoryController:: class, 'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{subCategory}/edit',[SubCategoryController:: class, 'edit'])->name('sub-categories.edit');
        Route::post('/sub-categories/{subCategory}',[SubCategoryController:: class, 'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategory}',[SubCategoryController:: class, 'destroy'])->name('sub-categories.destroy');

        // brand routes
        Route::get('/brand/create',[BrandController:: class, 'create'])->name('brand.create');
        Route::get('/brands',[BrandController:: class, 'index'])->name('brand.index');
        Route::post('/brands',[BrandController:: class, 'store'])->name('brand.store');
        Route::get('/brands/{brand}/edit',[BrandController:: class, 'edit'])->name('brand.edit');
        Route::put('/brands/{brand}',[BrandController:: class, 'update'])->name('brand.update');
        Route::delete('/brands/{brand}',[BrandController:: class, 'destroy'])->name('brand.destroy');

        // Product Routes
        Route::get('/product',[ProductController:: class, 'index'])->name('product.index');
        Route::get('/product/create',[ProductController:: class, 'create'])->name('product.create');
        Route::post('/product',[ProductController:: class, 'store'])->name('product.store');
        Route::get('/product/{product}/edit',[ProductController:: class, 'edit'])->name('product.edit');
        Route::put('/product/{product}',[ProductController:: class, 'update'])->name('product.update');
        Route::delete('/product/{product}',[ProductController:: class, 'destroy'])->name('product.destroy');
        Route::get('/get-products', [ProductController:: class, 'getProducts'])->name('product.getProducts');


        // Product sub categories Routes
        Route::get('/product-subcategories',[ProductSubCategoryController:: class, 'index'])->name('product-subcategories.index');

        // temp image
        Route::post('/upload-temp-image',[TempImagesController:: class, 'create'])->name('temp-images.create');

        // product image save
        Route::post('/product-images/update',[ProductImageController:: class, 'update'])->name('product-images.update');
        Route::delete('/product-images',[ProductImageController:: class, 'destroy'])->name('product-images.destroy');



        Route::get('/getSlug', function(Request $request){
            $slug = '';
            if(!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');
    });



});
