<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts_draft = Post::orderBy('created_at', 'desc')
            ->where('status', 'Rascunho')
            ->paginate(5);

        $posts = Post::orderBy('created_at', 'desc')
            ->where('status', 'Publish')
            ->paginate(5);
        return view('dashboard.post.index', ['collection' => $posts, 'posts_draft' => $posts_draft]);
    }

    public function trash()
    {
        $posts = Post::orderBy('created_at', 'desc')
            ->onlyTrashed()
            ->paginate(5);

        return view('dashboard.post.trash', compact('posts'));
    }

    public function posts()
    {
        $posts = Post::select('title', 'slug', 'thumbnail')
            ->where('created_at', '<=', date('Y-m-d H:i:s'))
            ->where('status', 'Publish')
            ->orderBy('created_at', 'desc')->paginate(10);

        return view('post.index', [
            'collection' => $posts
        ]);
    }

    public function postsByCat($category)
    {
        $cat = Category::select('id', 'title')
            ->where('slug', $category)
            ->first();

        $posts = PostCategory::where('category', $cat->id)
            ->where('posts.created_at', '<=', date('Y-m-d H:i:s'))
            ->where('status', 'Publish')
            ->orderBy('post_categories.created_at', 'desc')
            ->select('posts.title', 'posts.slug', 'posts.thumbnail')
            ->join('posts', 'post_categories.post', '=', 'posts.id')
            ->paginate(10);

        return view('post.categoria', [
            'collection' => $posts,
            'category' => $category,
            'og_title' => "Lista de posts da categoria $cat->title",
            'description' => "Leia sobre <strong>$cat->title</strong> agora mesmo, aqui estão alguns artigos sobre o tema:",
        ]);
    }

    public function postsByTag($tag)
    {
        $tag = Tag::where('slug', $tag)->first();

        $posts = PostTag::where('tag', $tag->id)
            ->where('posts.created_at', '<=', date('Y-m-d H:i:s'))
            ->where('status', 'Publish')
            ->orderBy('post_tags.created_at', 'desc')
            ->select('posts.title', 'posts.slug', 'posts.thumbnail')
            ->join('posts', 'post_tags.post', '=', 'posts.id')
            ->paginate(10);

        return view('post.tag', [
            'collection' => $posts,
            'tag' => $tag->title,
            'og_title' => "Lista de posts da tag $tag->title",
            'description' => "Leia sobre <strong>$tag->title</strong> agora mesmo, aqui estão alguns artigos sobre o tema:",
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::get();

        return view('dashboard.post.create', ['cats' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate_f = [
            'title' => 'required|min:3|unique:posts',
            'status' => 'required',
        ];

        if ($request->status == 'Publish') {
            $validate_f = [
                'abstract' => 'required|min:10',
                'body' => 'required',
                'author' => 'required',
                'thumbnail' => 'required',
                'categories' => 'required',
                'tags' => 'required',
            ];
        }

        $validated = $request->validate($validate_f);

        $legend = ($request->img_legend == null) ? $request->title : $request->img_legend;
        $thumbnail = json_encode([$request->thumbnail, $legend]);

        $data = [
            'title' => $request->title,
            'abstract' => $request->abstract,
            'body' => $request->body,
            'status' => ($request->status == 'Publicado') ? 'Publish' : 'Rascunho',
            'author' => $request->author,
            'user_create' => auth()->user()->id,
            'user_last_edit' => auth()->user()->id,
            'thumbnail' => $thumbnail,
        ];

        if ($request->created_at) {
            $data['created_at'] = $request->created_at;
        }

        $save = Post::create($data);

        if ($request->categories != null) {
            foreach (json_decode($request->categories) as $category) {
                $cat = Category::where('title', $category)->first();
                if (!$cat) {
                    $cat = Category::create([
                        'title' => $category
                    ]);
                }
                $this->postCategory($save->id, $cat->id);
            }
        }

        if ($request->tags != null) {
            foreach (json_decode($request->tags) as $tag) {
                $tagg = Tag::where('title', $tag)->first();
                if (!$tagg) {
                    $tagg = Tag::create(['title' => $tag]);
                }
                $this->postTag($save->id, $tagg->id);
            }
        }

        return redirect(route('dashboard-posts-edit', $save->id))->with('success', 'Atualizado com sucesso.');
    }

    public function postCategory($post, $category)
    {
        return PostCategory::create([
            'post' => $post,
            'category' => $category,
        ]);
    }

    public function postTag($post, $tag)
    {
        return PostTag::create([
            'post' => $post,
            'tag' => $tag,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::where('id', $id)->first();
        return view('dashboard.post.show', ['post' => $post, 'title' => $post->title]);
    }

    public function single($slug)
    {
        $post = Post::select(
            'id',
            'title',
            'author',
            'thumbnail',
            'body',
            'abstract'
        )
            ->where('slug', $slug)
            ->orderBy('created_at', 'desc')
            ->first();

        $cats_list = PostCategory::select('category')
            ->where('post', $post->id)
            ->get(); // Lista as categorias do post

        $similar_cat_list = [];
        $cats = [];

        foreach ($cats_list as $cat) {
            $cats[] = Category::select('slug', 'title')
                ->where('id', $cat->category)
                ->first();

            $similar_cat_list[] = $cat->category;
        }

        $tags_list = PostTag::where('post', $post->id)->get();
        $tags = [];
        foreach ($tags_list as $tag) {
            $tags[] = Tag::where('id', $tag->tag)->first();
        }

        // $similiar_list = [];
        // foreach ($similar_cat_list as $sim_cat) {
        //     $similiar_list[] = PostCategory::where('category', $sim_cat)->first();
        // }

        $similiar = Post::whereNot('id', $post->id)->get();
        // dd($similiar);
        // /*
        //  * Remover post atual da lista
        //  */
        // foreach ($similiar_list as $item) {
        //     if ($item->post == $post->id) {
        //         continue;
        //     }
        //     $similiar[] = Post::where('id', $item->post)->first();
        // }

        // dd($similiar_list);
        return view('post.show', [
            'post' => $post,
            'categories' => $cats,
            'tags' => $tags,
            'author' => Author::find($post->author),
            'similiar' => $similiar,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::where('id', $id)->first();
        $cats_list = PostCategory::where('post', $post->id)->get();
        $cats = [];
        foreach ($cats_list as $cat) {
            $now = Category::where('id', $cat->category)->first();
            $cats[] = $now->title;
        }
        $tags_list = PostTag::where('post', $post->id)->get();
        $tags = [];
        foreach ($tags_list as $tag) {
            $now = Tag::where('id', $tag->tag)->first();
            $tags[] = $now->title;
        }

        $all_cats = Category::get();

        return view('dashboard.post.edit', [
            'post' => $post,
            'all_cats' => $all_cats,
            'categories' => $cats,
            'tags' => $tags,
        ]);
    }

    private function getCategories($post_id)
    {
        $categories = postCategory::select(
            "categories.title as title",
            "categories.id as id",
        )
            ->join("categories", "categories.id", "=", "post_categories.category")
            ->where('post', $post_id)
            ->get();

        $categories_to_post = [];

        foreach ($categories as $cat) {
            $categories_to_post[] = [
                'title' => $cat->title,
                'value' => $cat->id,
            ];
        }

        return json_encode($categories_to_post);
    }

    private function getTags($post_id)
    {
        $tags = postTag::select(
            "tags.title as title",
            "tags.id as id",
        )
            ->join("tags", "tags.id", "=", "post_tags.tag")
            ->where('post', $post_id)
            ->get();

        $tags_to_post = [];

        foreach ($tags as $tag) {
            $tags_to_post[] = [
                'title' => $tag->title,
                'value' => $tag->id,
            ];
        }

        return json_encode($tags_to_post);
    }

    /**
     * Process abstract default when not fill in form.
     *
     * @param $text 
     * @return $abstract
     */
    private function get_abstract($text)
    {
        $remove_html = strip_tags($text);
        $words = explode(' ', $remove_html);
        $abstract = '';
        for ($i = 0; $i < 20; $i++) {
            $abstract .= $words[$i] . ' ';
        }
        $abstract .= '...';
        return $abstract;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        // dd($request->all());
        $validate_f = [
            'id' => 'required',
            'title' => 'required|min:3|unique:posts,title,' . $request->id,
            'status' => 'required',
        ];

        if ($request->status == 'Publish') {
            $validate_f = [
                'abstract' => 'required|min:10',
                'body' => 'required',
                'author' => 'required',
                'thumbnail' => 'required',
                'categories' => 'required',
                'tags' => 'required',
            ];
        }

        $post = Post::find($request->id);

        $request->validate($validate_f);

        if ($request->abstract != null || $request->body != null) {
            $abstract = $request->abstract == null ? $this->get_abstract($request->body) : $request->abstract;
            $post->abstract = $abstract;
            $post->body = $request->body;
        }

        // Update categories and tas, first remove all items of post updated, after add new items
        if ($request->categories != null) {
            $deletedCats = postCategory::where('post', $request->id)->delete();
            foreach (json_decode($request->categories) as $category) {
                $cat = Category::where('title', $category)->first();
                if (!$cat) {
                    $cat = Category::create(['title' => $category]);
                }
                $this->postCategory($request->id, $cat->id);
            }
        }

        if ($request->tags != null) {
            $deletedTags = postTag::where('post', $request->id)->delete();
            foreach (json_decode($request->tags) as $tag) {
                $tagg = Tag::where('title', $tag)->first();
                if (!$tagg) {
                    $tagg = Tag::create(['title' => $tag]);
                }
                $this->postTag($request->id, $tagg->id);
            }
        }

        // Prepare JSON thumbnail
        $legend = ($request->img_legend == null) ? $request->title : $request->img_legend;
        $thumbnail = json_encode([$request->thumbnail, $legend]);
        $post->thumbnail = $thumbnail;

        $post->title = $request->title;
        if ($request->created_at) {
            $post->created_at = $request->created_at;
        }
        $post->status = ($request->status == 'Publicado' || $request->status == 'Publish') ? 'Publish' : 'Rascunho';
        $post->author = $request->author;
        $post->created_at = $request->created_at;
        $post->save();

        return back()->with('success', 'Atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, $id)
    {
        Post::destroy($id);

        return redirect()->back()->with('error', 'Deletado com sucesso.');
    }

    public function restore($id)
    {
        $post = Post::withTrashed()->find($id);
        $post->deleted_at = null;
        $post->save();

        return redirect()->back()->with('success', 'Restaurado com sucesso.');
    }

    public function forceDelete($id)
    {
        $post = Post::withTrashed()->find($id);
        $post->forceDelete();

        return redirect()->back()->with('success', 'Deletado com sucesso.');
    }

    public function search(Request $request)
    {
        $posts = Post::select('id', 'title', 'slug')
            ->where('title', 'LIKE', '%' . $request->term . '%')
            ->orWhere('abstract', 'LIKE', '%' . $request->term . '%')
            ->orWhere('body', 'LIKE', '%' . $request->term . '%')
            ->get();

        return response()->json([
            'posts' => $posts,
        ]);
    }

    public function autofill($cat)
    {
        for ($i = 1; $i <= 192; $i++) {
            $this->postCategory($i, $cat);
        }
    }
}
