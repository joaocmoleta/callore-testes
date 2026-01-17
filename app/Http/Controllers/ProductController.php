<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TechnicalSpecification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $products = Product::select('*')
        $products = Product::select(
            'title',
            'value',
            'slug',
            'id',
            'images',
            'qtd'
        )
            ->withTrashed()
            ->paginate(4);

        // dd($products);

        return view('dashboard.products.index', compact('products'));
    }

    public function front_all()
    {
        $products = Cache::remember('products', 60, function () {
            return Product::select(
                'title',
                'value',
                'slug',
                'id',
                'images'
            )
                ->orderBy('title', 'ASC')
                ->get();
        });


        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'sku' => 'nullable|string',
                'title' => 'required|min:3|unique:products',
                'group' => 'nullable',
                'model' => 'nullable',
                'value' => 'required|numeric',
                'discount' => 'nullable|numeric',
                'qtd' => 'required',
                'abstract' => 'required',
                'type' => 'string|nullable',
                'color' => 'string|nullable',
                'voltage' => 'numeric|nullable',
                'wattage' => 'numeric|nullable',
                'cable' => 'string|nullable',
                'material' => 'string|nullable',
                'paint' => 'string|nullable',
                'suporte_paredes' => 'string|nullable',
                'suporte_chao' => 'string|nullable',
                'indicate' => 'string|nullable',
                'sizes_h' => 'numeric|nullable',
                'sizes_w' => 'numeric|nullable',
                'sizes_l' => 'numeric|nullable',
                'sizes_we' => 'numeric|nullable',
                'pack_sizes_h' => 'numeric|nullable',
                'pack_sizes_w' => 'numeric|nullable',
                'pack_sizes_l' => 'numeric|nullable',
                'pack_sizes_we' => 'numeric|nullable',
                'line' => 'string|nullable',
                'manufacturer' => 'string|nullable',
                'guarantee' => 'numeric|nullable',
                'description' => 'required',
                'images' => 'required',
            ],
            [
                'title' => 'Adicione um título de no mínimo 3 caracteres.',
                'title.unique' => 'Este título já está sendo utilizado.',
                'value' => 'Defina o preço do produto.',
                'abstract' => 'Adicione um resumo do produto.'
            ]
        );

        unset($validated['sizes_h']);
        unset($validated['sizes_w']);
        unset($validated['sizes_l']);
        unset($validated['sizes_we']);
        unset($validated['pack_sizes_h']);
        unset($validated['pack_sizes_w']);
        unset($validated['pack_sizes_l']);
        unset($validated['pack_sizes_we']);

        $save = Product::create($validated);

        if (!$save) {
            return redirect()->back()->with('error', 'Falha ao adicionar o produto.');
        }

        $validated['sizes'] = json_encode([
            'h' => $request->sizes_h,
            'w' => $request->sizes_w,
            'l' => $request->sizes_l,
            'we' => $request->sizes_we,
        ]);

        $validated['pack_sizes'] = json_encode([
            'h' => $request->pack_sizes_h,
            'w' => $request->pack_sizes_w,
            'l' => $request->pack_sizes_l,
            'we' => $request->pack_sizes_we,
        ]);

        $validated['product'] = $save->id;

        $techSpec = TechnicalSpecification::create($validated);

        if (!$techSpec) {
            return redirect()->back()->with('error', 'Falha ao adicionar o produto.');
        }

        return redirect(route('products.index'))->with('success', 'Produto adicionado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $tech_spec = TechnicalSpecification::select('*')
            ->where('product', $product->id)
            ->first();

        return view('dashboard.products.edit', compact('product', 'tech_spec'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $tech_spec = TechnicalSpecification::select('*')
            ->where('product', $product->id)
            ->first();

        return view('dashboard.products.edit', compact('product', 'tech_spec'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'nullable|string',
            'title' => 'required|min:3|unique:products,title,' . $product->id,
            'group' => 'required',
            'model' => 'required',
            'value' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'qtd' => 'required',
            'abstract' => 'required',
            'type' => 'string|nullable',
            'color' => 'string|nullable',
            'voltage' => 'numeric|nullable',
            'wattage' => 'numeric|nullable',
            'cable' => 'string|nullable',
            'material' => 'string|nullable',
            'paint' => 'string|nullable',
            'suporte_paredes' => 'string|nullable',
            'suporte_chao' => 'string|nullable',
            'indicate' => 'string|nullable',
            'sizes_h' => 'numeric|nullable',
            'sizes_w' => 'numeric|nullable',
            'sizes_l' => 'numeric|nullable',
            'sizes_we' => 'numeric|nullable',
            'pack_sizes_h' => 'numeric|nullable',
            'pack_sizes_w' => 'numeric|nullable',
            'pack_sizes_l' => 'numeric|nullable',
            'pack_sizes_we' => 'numeric|nullable',
            'line' => 'string|nullable',
            'manufacturer' => 'string|nullable',
            'guarantee' => 'numeric|nullable',
            'description' => 'required',
            'images' => 'required',
        ]);

        $product->sku = $request->sku;
        $product->title = $request->title;
        $product->group = $request->group;
        $product->model = $request->model;
        $product->value = $request->value;
        $product->discount = $request->discount;
        $product->qtd = $request->qtd;
        $product->abstract = $request->abstract;
        $product->description = $request->description;
        $product->images = $request->images;

        // dd($product->isDirty());

        if ($product->isDirty()) {
            $save = $product->save();

            if (!$save) {
                return redirect()->back()->with('error', 'Falha ao atualizar o produto.');
            }
        }

        $techSpec = TechnicalSpecification::select('id')
            ->where('product', $product->id)
            ->update([
                'product' => $product->id,
                'type' => $request->type,
                'color' => $request->color,
                'voltage' => $request->voltage,
                'wattage' => $request->wattage,
                'cable' => $request->cable,
                'material' => $request->material,
                'paint' => $request->paint,
                'suporte_paredes' => $request->suporte_paredes,
                'suporte_chao' => $request->suporte_chao,
                'indicate' => $request->indicate,
                'sizes' => json_encode([
                    'h' => $request->sizes_h,
                    'w' => $request->sizes_w,
                    'l' => $request->sizes_l,
                    'we' => $request->sizes_we,
                ]),
                'pack_sizes' => json_encode([
                    'h' => $request->pack_sizes_h,
                    'w' => $request->pack_sizes_w,
                    'l' => $request->pack_sizes_l,
                    'we' => $request->pack_sizes_we,
                ]),
                'line' => $request->line,
                'manufacturer' => $request->manufacturer,
                'guarantee' => $request->guarantee,
            ]);


        if (!$techSpec) {
            return redirect()->back()->with('error', 'Falha ao atualizar o produto.');
        }

        return redirect()->back()->with('success', 'Produto atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (!$product->delete()) {
            return back()->with('error', 'Erro ao deletar.');
        }
        return back()->with('success', 'Deletado com sucesso.');
    }

    public function restore($product)
    {
        if (!Product::withTrashed()->find($product)->restore()) {
            return back()->with('error', 'Erro ao restaurar.');
        }
        return back()->with('success', 'Restaurado com sucesso.');
    }

    public function removeTrash($product)
    {
        $product_db = Product::withTrashed()->find($product);

        if (!$product_db->forceDelete()) {
            return back()->with('error', 'Falha ao deletar o produto.');
        }

        $tech = TechnicalSpecification::find($product, 'product');

        if (!$tech->forceDelete()) {
            return back()->with('error', 'Falha ao deletar as especificações técnicas.');
        }

        return back()->with('success', 'Removido com sucesso.');
    }

    public function list()
    {
        // $products = Product::select(
        //     'id',
        //     'title',
        //     'slug',
        //     'value',
        //     'discount',
        //     'images',
        //     'qtd'
        // )
        //     ->orderBy('title')
        //     ->get();

        //     dd(json_encode($products->toArray()));
                
        // $get_products = file_get_contents(Config::get('view.paths') . '/products/products.json.php');

        // dd(Config::get('view.paths')[0]);

        // $products = json_decode('[{"title": "Aquecedor de Toalhas Versátil", "price": 1754.54, "discount": null, "qtd": 1, "colors": [{"color": "black", "title": "Preto", "url": "aquecedor-de-toalhas-callore-versatil-preto-127v", "src": "img/aquecedor-de-toalhas-versatil-preto.webp", "pick_url": "img/acabamento-aquecedor-de-toalhas-callore-preto.webp"}, {"color": "white", "title": "Branco", "url": "aquecedor-de-toalhas-callore-versatil-branco-127v", "src": "img/aquecedor-de-toalhas-versatil-branco.webp", "pick_url": "img/acabamento-aquecedor-de-toalhas-callore-branco.webp"}, {"color": "bege", "title": "Bege", "url": "aquecedor-de-toalhas-callore-versatil-bege-127v", "src": "img/aquecedor-de-toalhas-versatil-bege.webp", "pick_url": "img/acabamento-aquecedor-de-toalhas-callore-bege.webp"}], "other_infos": [{"name": "v127220", "text": "127 ou 220V"}]}, {"title": "Aquecedor de Toalhas Compacto", "price": 1928.62, "discount": null, "qtd": 1, "colors": [{"color": "black", "title": "Preto", "url": "aquecedor-de-toalhas-callore-compacto-preto-127v", "src": "img/aquecedor-de-toalhas-compacto-preto.webp", "pick_url": "img/acabamento-aquecedor-de-toalhas-callore-preto.webp"}, {"color": "white", "title": "Branco", "url": "aquecedor-de-toalhas-callore-compacto-branco-127v", "src": "img/aquecedor-de-toalhas-compacto-branco.webp", "pick_url": "img/acabamento-aquecedor-de-toalhas-callore-branco.webp"}, {"color": "bege", "title": "Bege", "url": "aquecedor-de-toalhas-callore-compacto-bege-127v", "src": "img/aquecedor-de-toalhas-compacto-bege.webp", "pick_url": "img/acabamento-aquecedor-de-toalhas-callore-bege.webp"}], "other_infos": [{"name": "v127220", "text": "127 ou 220V"}]}, {"title": "Aquecedor de Toalhas Família", "price": 2328.40, "discount": null, "qtd": 1, "colors": [{"color": "black", "title": "Preto", "url": "aquecedor-de-toalhas-callore-familia-preto-127v", "src": "img/aquecedor-de-toalhas-familia-preto.webp", "pick_url": "img/acabamento-aquecedor-de-toalhas-callore-preto.webp"}, {"color": "white", "title": "Branco", "url": "aquecedor-de-toalhas-callore-familia-branco-127v", "src": "img/aquecedor-de-toalhas-familia-branco.webp", "pick_url": "img/acabamento-aquecedor-de-toalhas-callore-branco.webp"}, {"color": "bege", "title": "Bege", "url": "aquecedor-de-toalhas-callore-familia-bege-127v", "src": "img/aquecedor-de-toalhas-familia-bege.webp", "pick_url": "img/acabamento-aquecedor-de-toalhas-callore-bege.webp"}], "other_infos": [{"name": "v127220", "text": "127 ou 220V"}]}, {"title": "Aquecedor de Toalhas Stilo 8", "price": 2613, "discount": null, "qtd": 0, "colors": [{"color": "chromo", "title": "Cromado", "url": "aquecedor-de-toalhas-callore-stilo-8-127v", "src": "img/aquecedor-de-toalhas-stilo-8.webp"}], "other_infos": [{"name": "v127220", "text": "127 ou 220V"}]}, {"title": "Aquecedor de Toalhas Stilo 10", "price": 3224.54, "discount": null, "qtd": 0, "colors": [{"color": "chromo", "title": "Cromado", "url": "aquecedor-de-toalhas-callore-stilo-10-127v", "src": "img/aquecedor-de-toalhas-stilo-10.webp"}], "other_infos": [{"name": "v127220", "text": "127 ou 220V"}]}, {"title": "Suporte Móvel com rodinhas para Aquecedor Callore", "price": 460, "discount": null, "qtd": 1, "colors": [{"color": "black", "title": "Preto", "url": "suporte-de-chao-com-rodinhas-preto", "src": "img/suporte-movel-com-rodinhas-preto-2.webp"}, {"color": "white", "title": "Branco", "url": "suporte-de-chao-com-rodinhas-branco", "src": "img/suporte-movel-com-rodinhas-branco.webp"}, {"color": "bege", "title": "Bege", "url": "suporte-de-chao-com-rodinhas-bege", "src": "img/suporte-movel-com-rodinhas-bege.webp"}]}, {"title": "Suporte Móvel sem rodinhas para Aquecedor Callore", "price": 200, "discount": null, "qtd": 1, "colors": [{"color": "black", "title": "Preto", "url": "suporte-de-chao-sem-rodinhas-preto", "src": "img/suporte-movel-preto-3.webp"}, {"color": "white", "title": "Branco", "url": "suporte-de-chao-sem-rodinhas-branco", "src": "img/suporte-movel-branco.webp"}, {"color": "bege", "title": "Bege", "url": "suporte-de-chao-sem-rodinhas-bege", "src": "img/suporte-movel-bege.webp"}]}]');

        $products = json_decode(file_get_contents(Config::get('view.paths')[0] . '/products/products.json.php'));


        $user_logged = auth()->user();

        $cart = \App\Helpers\CartHelper::instance()->check_cart();

        // dd($products);

        return view('products.index', compact('products', 'cart'));
    }

    public function single($slug, Product $pro)
    {
        $product = $pro->select(
            'id',
            'title',
            'slug',
            'value',
            'discount',
            'qtd',
            'abstract',
            'description',
            'images',
            'group',
            'model',
        )
            ->where(['slug' => $slug])->firstOrFail();

        $other_voltage = $pro->select('id', 'slug')
            ->where('model', $product->model)
            ->where('id', '<>', $product->id)
            ->first();

        $tech_spec = TechnicalSpecification::select(
            'type',
            'color',
            'voltage',
            'wattage',
            'cable',
            'material',
            'paint',
            'indicate',
            'suporte_paredes',
            'suporte_chao',
            'sizes',
            'pack_sizes',
            'line',
            'manufacturer',
            'guarantee'
        )
            ->where('product', $product->id)
            ->first();

        $sizes = json_decode($tech_spec->sizes);

        $pack_sizes = json_decode($tech_spec->pack_sizes);

        $cart = \App\Helpers\CartHelper::instance()->check_cart();

        $similiars = Product::select(
            'id',
            'title',
            'value',
            'images',
            'slug',
            'discount',
            'model'
        )
            ->where('id', '<>', $product->id)
            // ->where('group', $product->group)
            ->groupBy('model')
            // ->having(DB::raw('count(model)'), '>', 1)
            // ->havingRaw('count(model) > ?', [1])
            // ->limit(6)
            ->get();

        // foreach ($similiars as $item) {
        //     $exclude[] = $item->id;
        // }

        // $exclude[] = $product->id;

        // $more = Product::select(
        //     'products.id',
        //     'products.title',
        //     'products.value',
        //     'products.slug',
        //     'products.images',
        //     'products.discount',
        // )
        //     ->whereNotIn('products.id', $exclude)
        //     ->limit(4)
        //     ->get();

        return view('single', compact('product', 'cart', 'tech_spec', 'sizes', 'pack_sizes', 'similiars', 'other_voltage'));
    }
}
