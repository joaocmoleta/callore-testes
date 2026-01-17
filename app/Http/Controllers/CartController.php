<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Jobs\SearchIP;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carts = Cart::select(
            'carts.id',
            'carts.ip',
            'carts.coupon',
            'carts.amount',
            'carts.created_at',
            'carts.updated_at',
            'carts.deleted_at',
            'us.name',
            'ips.country',
            'ips.region',
            'ips.city',
            'ips.district',
        )
            ->leftJoin('users as us', 'us.id', 'carts.user')
            ->leftJoin('i_p_s as ips', 'ips.ip', 'carts.ip')
            ->withTrashed()
            ->orderBy('id', 'DESC')
            ->paginate(8);

        foreach ($carts as $cart) {
            $ips[] = $cart->ip;
        }

        SearchIP::dispatch($ips);

        return view('dashboard.carts.index', compact('carts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('dashboard.carts.create');
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
            'user' => 'required',
            'products' => 'required',
            'qtd' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        if (!Cart::create($validated)) {
            return redirect()->back()->with('error', 'Falha ao adicionar.');
        }

        return redirect(route('carts.index'))->with('success', 'Adicionado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show($cart)
    {
        $cart_and_more = Cart::select(
            'carts.products',
            'carts.qtd',
            'carts.amount',
            'carts.created_at',
            'carts.updated_at',
            'carts.deleted_at',
            'i_p_s.ip',
            'i_p_s.country',
            'i_p_s.region',
            'i_p_s.city',
            'i_p_s.district',
            'i_p_s.cep',
            'users.name',
            'users.email',
            'users.phone',
            'users.created_at',
            'users.updated_at',
        )
            ->where('carts.id', $cart)
            ->leftJoin('i_p_s', 'i_p_s.ip', 'carts.ip')
            ->leftJoin('users', 'users.id', 'carts.user')
            ->withTrashed()
            ->first();


        $products = [];
        $products_ref = json_decode($cart_and_more->products);
        foreach ($products_ref as $product) {
            $products[] = [
                'title' => Product::select('title')->where('id', $product->product)->first()->title,
                'qtd' => $product->qtd,
                'value_uni' => $product->value_uni,
            ];
        }

        // dd($cart_and_more->toArray());
        // dd($products);

        return view('dashboard.carts.show', compact('cart_and_more', 'products', 'cart'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        return view('dashboard.carts.edit', compact('cart'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        $validated = $request->validate([
            'user' => 'required',
            'products' => 'required',
            'qtd' => 'required',
            'amount' => 'required|numeric',
        ]);

        $cart->user = $request->user;
        $cart->products = $request->products;
        $cart->qtd = $request->qtd;
        $cart->amount = $request->amount;

        if (!$cart->save()) {
            return redirect()->back()->with('error', 'Falha ao atualizar.');
        }

        return redirect()->back()->with('success', 'Atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        if (!$cart->delete()) {
            return back()->with('error', 'Erro ao deletar.');
        }
        return back()->with('success', 'Deletado com sucesso.');
    }

    public function restore($cart)
    {
        if (!Cart::withTrashed()->find($cart)->restore()) {
            return back()->with('error', 'Erro ao restaurar.');
        }
        return back()->with('success', 'Restaurado com sucesso.');
    }

    public function add($product, Request $req)
    {
        $req->validate(
            [
                'qtd' => 'required|integer|min:1',
            ],
            [
                'qtd' => 'Quantidade deve ser um número maior que 1.',
            ]
        );

        $db_product = Product::select('value')->where('id', $product)->first();

        if ($cart = CartHelper::instance()->check_cart()) {
            if (CartHelper::instance()->update_cart([$product, $req->qtd], $cart)) {
                return redirect(route('carts.list'))->with('success', 'Adicionado ao seu carrinho.');
            } else {
                return redirect()->back()->with('error', 'Falha ao adicionar.');
            }
        } else {
            $new_cart = array();
            $new_cart['ip'] = $req->ip();
            $new_cart['products'] = json_encode([[
                'product' => $product,
                'qtd' => $req->qtd,
                'value_uni' => $db_product->value
            ]]);
            $new_cart['qtd'] = $req->qtd;
            $new_cart['amount'] = $db_product->value * $req->qtd;

            if (CartHelper::instance()->create_cart($new_cart)) {
                return redirect(route('carts.list'))->with('success', 'Adicionado ao seu carrinho.');
            } else {
                return redirect()->back()->with('error', 'Falha ao adicionar.');
            }
        }
    }

    public function remove($product)
    {
        $cart = CartHelper::instance()->check_cart();

        $new_products = [];
        $qtd_geral = 0;
        $amount = 0;
        foreach (json_decode($cart->products) as $item) {
            if ($item->product != $product) {
                $new_products[] = [
                    'product' => $item->product,
                    'qtd' => $item->qtd,
                    'value_uni' => $item->value_uni
                ];
                $qtd_geral += $item->qtd;
                $amount += $item->value_uni * $item->qtd;
            }
        }

        $cart->products = json_encode($new_products);
        $cart->amount = $amount;
        $cart->qtd = $qtd_geral;

        if (!$cart->save()) {
            return redirect()->back()->with('error', 'Falha ao atualizar.');
        }

        return redirect()->back()->with('success', 'Carrinho atualizado.');
    }

    public function cart_list()
    {
        $cart_db = CartHelper::instance()->check_cart();

        $cart_list = [];
        $amount = 0;
        $qtd = 0;
        if ($cart_db) {
            foreach (json_decode($cart_db->products) as $item) {
                $product = Product::find($item->product);

                if (!$product) {
                    CartHelper::instance()->remove_item($item->product, $cart_db);
                    continue;
                }
                $amount += $product->value * $item->qtd;
                $qtd += $item->qtd;
                $cart_list[] = [
                    'id' => $item->product,
                    'name' => $product->title,
                    'slug' => $product->slug,
                    'thumbnail' => json_decode($product->images)[0],
                    'qtd' => $item->qtd,
                    'value_uni' => $product->value,
                    'subtotal' => $product->value * $item->qtd
                ];
            }
        }

        $cart = (object)[
            'qtd' => $qtd,
            'amount' => $amount,
            'coupon' => $cart_db->coupon ?? '',
        ];

        // Identificar suporte de chão
        // Verificar se tem, verificar compatibilidade com aquecedor selecionado
        // $aquecedores = [];
        // $suportes = [];
        // echo '<pre>';
        // foreach($cart_list as $item) {
        //     print_r($item);
        // }

        // exit();

        // dd($cart_list);

        return view('cart', compact('cart_list', 'cart'));
    }

    public function cart_reduce(Request $req)
    {
        $cart = CartHelper::instance()->check_cart();

        $new_products = [];
        $amount = 0;
        $qtd_geral = 0;
        foreach (json_decode($cart->products) as $item) {
            if ($item->product != $req->id_product) {
                $amount += $item->value_uni * $item->qtd;
                $qtd_geral += $item->qtd;
                $new_products[] = [
                    'product' => $item->product,
                    'qtd' => $item->qtd,
                    'value_uni' => $item->value_uni
                ];
            } else {
                if ($item->qtd > 1) {
                    $amount += $item->value_uni * ($item->qtd - 1);
                    $qtd_geral += $item->qtd - 1;
                    $new_products[] = [
                        'product' => $item->product,
                        'qtd' => $item->qtd - 1,
                        'value_uni' => $item->value_uni
                    ];
                }
            }
        }

        $cart->products = json_encode($new_products);
        $cart->amount = $amount;
        $cart->qtd = $qtd_geral;

        if (!$cart->save()) {
            return redirect()->back()->with('error', 'Falha ao atualizar carrinho.');
        }

        return redirect()->back()->with('success', 'Carrinho atualizado.');
    }

    public function cart_plus(Request $req)
    {
        $cart = CartHelper::instance()->check_cart();

        $new_products = [];
        $amount = 0;
        $qtd_geral = 0;
        foreach (json_decode($cart->products) as $item) {
            if ($item->product != $req->id_product) {
                $amount += $item->value_uni * $item->qtd;
                $qtd_geral += $item->qtd;
                $new_products[] = [
                    'product' => $item->product,
                    'qtd' => $item->qtd,
                    'value_uni' => $item->value_uni
                ];
            } else {
                $amount += $item->value_uni * ($item->qtd + 1);
                $qtd_geral += $item->qtd + 1;
                $new_products[] = [
                    'product' => $item->product,
                    'qtd' => $item->qtd + 1,
                    'value_uni' => $item->value_uni
                ];
            }
        }

        $cart->products = json_encode($new_products);
        $cart->amount = $amount;
        $cart->qtd = $qtd_geral;

        if (!$cart->save()) {
            return redirect()->back()->with('error', 'Falha ao atualizar carrinho.');
        }

        return redirect()->back()->with('success', 'Carrinho atualizado.');
    }
}
