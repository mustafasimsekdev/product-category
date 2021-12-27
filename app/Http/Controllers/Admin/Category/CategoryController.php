<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\CategoryLogs;
use App\Models\User;
use Dflydev\DotAccessData\Data;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('admin.category.list', compact('users'));
    }

    public function add()
    {
        $categories = Categories::all();
        return view('admin.category.add', compact('categories'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name,',
        ]);

        if ($validator->fails()) {
            $array['result'] = 0;
            $array['content_text'] = $validator->errors()->first();
        } else {
            $category = new Categories();
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->parent_id = $request->category_select2;
            if ($category->save()) {
                $categoryLog = new CategoryLogs();
                $categoryLog->category_id = $category->id;
                $categoryLog->creator_id = Auth::user()->id;
                $categoryLog->log_id = 1;
                $categoryLog->old_log_id = null;
                if ($categoryLog->save()) {
                    $array['result'] = 1;
                    $array['content_text'] = "Kategori başarıyla eklendi";
                } else {
                    $array['result'] = 1;
                    $array['content_text'] = "Kategori başarıyla eklendi fakat gecmis eklenmedi";
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Categories::find($id);
        if (isset($category)) {
            $mainCategories = Categories::where('id', '!=', $category->id)->get();
            $histories = CategoryLogs::where("category_id", $id)->get();
            if (isset($category->parent_id)) {
                $mainParentCategories = Categories::where('id', $category->parent_id)->get();
                $parent_categories = Categories::where('id', $mainParentCategories[0]->parent_id)->get();
                $mainParentCategories = $mainParentCategories->merge($parent_categories);
                while (count($parent_categories) > 0) {
                    $parent_categories = Categories::where('id', $parent_categories[0]->parent_id)->get();
                    $mainParentCategories = $mainParentCategories->merge($parent_categories);
                }
                $mainParentCategories = $mainParentCategories->sortBy('created_at');
                return view('admin.category.edit', compact('category', 'mainCategories', 'histories', 'mainParentCategories'));
            } else {
                return view('admin.category.edit', compact('category', 'mainCategories', 'histories'));
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name,' . $request->category_id,
        ]);

        if ($validator->fails()) {
            $array['result'] = 0;
            $array['content_text'] = $validator->errors()->first();

        } else {
            $updateCategory = Categories::where("id", $request->category_id)->first();
            $updateCategory->name = $request->name;
            $updateCategory->slug = Str::slug($request->name);
            $updateCategory->parent_id = $request->category_select2;
            if ($updateCategory->save()) {
                $getLastLog = CategoryLogs::where('category_id', $updateCategory->id)->orderBy('created_at', 'desc')->first();
                $categoryLog = new CategoryLogs();
                $categoryLog->category_id = $updateCategory->id;
                $categoryLog->creator_id = Auth::user()->id;

                $categoryLog->log_id = 2;
                $categoryLog->old_log_id = $getLastLog->log_id;

                if ($categoryLog->save()) {
                    $array['result'] = 1;
                    $array['content_text'] = "Kategori başarıyla guncellendi";
                } else {
                    $array['result'] = 1;
                    $array['content_text'] = "Kategori başarıyla guncellendi fakat gecmis eklenmedi";
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $category = $request->input('id');
        try {
            $mainCategories = Categories::where('id', $category)->get(['id']);
            $parent_categories = Categories::where('parent_id', $mainCategories)->get(['id']);
            $mainCategories = $mainCategories->merge($parent_categories);
            while (count($parent_categories) > 0) {
                $parent_categories = Categories::whereIn('parent_id', $parent_categories)->get(['id']);
                $mainCategories = $mainCategories->merge($parent_categories);
            }

            foreach ($mainCategories as $mainCategory) {
                $getLastChildLog = CategoryLogs::where('category_id', $mainCategory->id)->orderBy('created_at', 'desc')->first();

                $categoryChildLog = new CategoryLogs();
                $categoryChildLog->category_id = $mainCategory->id;
                $categoryChildLog->creator_id = Auth::user()->id;

                $categoryChildLog->log_id = 3;
                $categoryChildLog->old_log_id = $getLastChildLog->log_id;
                $categoryChildLog->save();
            }
            if ($mainCategories->each->delete()) {
                echo 'ok';
            } else {
                echo 'notok';
            }
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }

}
