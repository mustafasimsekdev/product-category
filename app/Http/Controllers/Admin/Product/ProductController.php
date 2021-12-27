<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\CategoryProduct;
use App\Models\ProductLogs;
use App\Models\Products;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Products::all();
        return view('admin.product.list', compact('products'));
    }

    public function add()
    {
        $mainCategory = Categories::where('parent_id', null)->get();
        $subCategory = Categories::where('parent_id', '!=', null)->get();
        return view('admin.product.add', compact('mainCategory', 'subCategory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name,',
            'description' => 'required',
            'price' => 'required',
            'user_image' => 'required',
        ]);

        if ($validator->fails()) {
            $array['result'] = 0;
            $array['content_text'] = $validator->errors()->first();
        } else {
            $newProduct = new Products();
            $newProduct->name = $request->name;
            $newProduct->description = $request->description;
            $newProduct->price = $request->price;
            $newProduct->slug = Str::slug($request->name);

            if ($request->hasFile('user_image')) {
                $file = $request->file('user_image');
                // if size less than 5MB
                if ($file->getSize() < 5000000) {
                    if (in_array($file->getClientOriginalExtension(), array('png', 'jpg', 'jpeg', 'gif'))) {
                        // upload
                        $avatar = Str::uuid() . "." . $file->getClientOriginalExtension();
                        $newProduct->photo = $avatar;
                        $file->storeAs("public/images/product-image/", $avatar);
                    }
                }
            }

            if ($newProduct->save()){
                foreach ($request->category_select2 as $category_select2) {
                    $categoryProduct = new CategoryProduct();
                    $categoryProduct->category_id = $category_select2;
                    $categoryProduct->product_id = $newProduct->id;
                    $categoryProduct->save();
                }
                $productLog = new ProductLogs();
                $productLog->product_id = $newProduct->id;
                $productLog->creator_id = Auth::user()->id;
                $productLog->log_id = 1;
                $productLog->old_log_id = null;
                if ($productLog->save()) {
                    $array['result'] = 1;
                    $array['content_text'] = "Urun başarıyla eklendi";
                } else {
                    $array['result'] = 1;
                    $array['content_text'] = "Urun başarıyla eklendi fakat gecmis eklenmedi";
                }
            } else {
                $array['result'] = 0;
                $array['content_text'] = "Beklenmeyen hatayla karşılaşıldı. Lütfen tekrar deneyin!";
            }
        }
        return response()->json(array('status' => $array['result'], 'message' => $array['content_text']));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Products::find($id);
        $mainCategory = Categories::where('parent_id', null)->get();
        $subCategory = Categories::where('parent_id', '!=', null)->get();
        $categoryProduct = CategoryProduct::where('product_id', $id)->get();
        $histories = ProductLogs::where("product_id", $id)->get();
        if (isset($product)) {
            return view('admin.product.edit', compact('product', 'mainCategory', 'subCategory', 'histories', 'categoryProduct'));
        } else {
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name,',
            'description' => 'required',
            'price' => 'required',
            'user_image' => 'required',
        ]);

        if ($validator->fails()) {
            $array['result'] = 0;
            $array['content_text'] = $validator->errors()->first();
        } else {
            $updateProduct = Products::where("id", $request->product_id)->first();
            $updateProduct->name = $request->name;
            $updateProduct->description = $request->description;
            $updateProduct->price = $request->price;
            $updateProduct->slug = Str::slug($request->name);

            if ($request->hasFile('user_image')) {
                $file = $request->file('user_image');
                // if size less than 5MB
                if ($file->getSize() < 5000000) {
                    if (in_array($file->getClientOriginalExtension(), array('png', 'jpg', 'jpeg', 'gif'))) {
                        // upload
                        $avatar = Str::uuid() . "." . $file->getClientOriginalExtension();
                        $updateProduct->photo = $avatar;
                        $file->storeAs("public/images/product-image/", $avatar);
                    }
                }
            }

            $updateProduct->is_active = $request->is_active == 'on' ? 1 : 0;

            if ($updateProduct->save()){
                $deleteCategoryProduct = CategoryProduct::where('product_id', $updateProduct->id)->get();
                if ($deleteCategoryProduct->each->delete()){
                    foreach ($request->category_select2 as $category_select2) {
                        $categoryProduct = new CategoryProduct();
                        $categoryProduct->category_id = $category_select2;
                        $categoryProduct->product_id = $updateProduct->id;
                        $categoryProduct->save();
                    }
                }

                $getLastLog = ProductLogs::where('product_id', $updateProduct->id)->orderBy('created_at', 'desc')->first();
                $productLog = new ProductLogs();
                $productLog->product_id = $updateProduct->id;
                $productLog->creator_id = Auth::user()->id;

                $productLog->log_id = 2;
                $productLog->old_log_id = $getLastLog->log_id;

                if ($productLog->save()) {
                    $array['result'] = 1;
                    $array['content_text'] = "Urun başarıyla guncellendi";
                } else {
                    $array['result'] = 1;
                    $array['content_text'] = "Urun başarıyla guncellendi fakat gecmis eklenmedi";
                }
            } else {
                $array['result'] = 0;
                $array['content_text'] = "Beklenmeyen hatayla karşılaşıldı. Lütfen tekrar deneyin!";
            }
        }
        return response()->json(array('status' => $array['result'], 'message' => $array['content_text']));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $product = $request->input('id');
        try {
            $delete = Products::findOrFail($product);
            $delete->is_active = 0;
            $delete->save();
            $delete->delete();

            echo 'ok';
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }
}
