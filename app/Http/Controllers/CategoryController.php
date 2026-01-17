<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('dashboard.category.index', ['collection' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:3|unique:categories',
        ]);

        if (!$validated) {
            return redirect()->back()->withInput();
        }

        $save = Category::create([
            'title' => $request->title,
        ]);

        return redirect(route('dashboard-categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::where('id', $id)->first();
        return view('dashboard.category.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::where('id', $id)->first();
        return view('dashboard.category.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $category = Category::where('id', $request->id)->first();
        $validated = $request->validate([
            'id' => 'required',
            'title' => 'required|min:3|unique:categories,title,' . $category->id,
        ]);

        if (!$validated) {
            return redirect()->back()->with('error', 'Falha ao atualizar a categoria.')->withInput();
        }

        $category = Category::find($request->id);

        $category->title = $request->title;

        $category->save();

        return redirect(route('dashboard-categories'))->with('success', 'Categoria atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, $id)
    {
        PostCategory::where('category', $id)->delete();

        Category::destroy($id);

        return redirect()->back()->with('success', 'Categoria deletada com sucesso.');
    }

    public function search($search)
    {
        $find = Category::where('title', 'LIKE', "%{$search}%")->get();

        return response()->json($find);
    }

    public function addBySearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|unique:categories',
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()]);
        }

        $save = Category::create([
            'title' => $request->title,
        ]);

        return response()->json($save);
    }
}
