<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors = Author::all();
        return view('dashboard.author.index', ['collection' => $authors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.author.create');
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
            'title' => 'required|unique:authors',
        ]);

        $save = Author::create([
            'title' => $request->title,
            'thumbnail' => ($request->thumbnail == null) ? '' : $request->thumbnail,
            'description' => ($request->description == null) ? '' : $request->description,
        ]);

        return redirect(route('dashboard-authors'))->with('success', 'Salvo com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author, $id)
    {
        $data = ['author' => $author->where('id', $id)->first()];
        return view('dashboard.author.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'title' => 'required|unique:authors,title,'.$request->id,
        ]);

        $a = $author->find($request->id);
        $a->title = $request->title;
        $a->thumbnail = ($request->thumbnail == null) ? '' : $request->thumbnail;
        $a->description = ($request->description == null) ? '' : $request->description;
        $a->save();

        return back()->with('success', 'Atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author, $id)
    {
        $author->destroy($id);

        return redirect()->back()->with('success', 'Deletado com sucesso.');
    }

    public function search($search)
    {
        $find = Author::where('title', 'LIKE', "%{$search}%")->get();

        return response()->json($find);
    }
}
