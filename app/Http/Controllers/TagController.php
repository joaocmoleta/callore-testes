<?php

namespace App\Http\Controllers;

use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return view('dashboard.tag.index', ['collection' => $tags]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.tag.create');
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
            'title' => 'required|min:3|unique:tags',
        ]);

        if (!$validated) {
            return redirect()->back()->withInput();
        }

        $save = Tag::create([
            'title' => $request->title,
        ]);

        return redirect(route('dashboard-tags'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::where('id', $id)->first();
        return view('dashboard.tag.show', ['tag' => $tag]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::where('id', $id)->first();
        return view('dashboard.tag.edit', ['tag' => $tag]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $tag = Tag::where('id', $request->id)->first();
        $validated = $request->validate([
            'id' => 'required',
            'title' => 'required|min:3|unique:tags,title,' . $tag->id,
        ]);

        if (!$validated) {
            return redirect()->back()->with('error', 'Falha ao atualizar a tag.')->withInput();
        }

        $tag = Tag::find($request->id);

        $tag->title = $request->title;

        $tag->save();

        return redirect(route('dashboard-tags'))->with('success', 'Tag atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag, $id)
    {
        PostTag::where('tag', $id)->delete();
        
        Tag::destroy($id);
        
        return redirect()->back()->with('success', 'Tag deletada com sucesso.');
    }

    public function search($search)
    {
        $find = Tag::where('title', 'LIKE', "%{$search}%")->get();

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

        $save = Tag::create([
            'title' => $request->title,
        ]);

        return response()->json($save);
    }
}
