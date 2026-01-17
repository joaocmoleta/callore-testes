<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products_db = Product::select('id', 'title')->get();
        $products = [];
        foreach ($products_db as $product) {
            $products[$product->id] = $product->title;
        }

        if ($request->get('filter') == 'all') {
            $coupons = Coupon::select('*')
            ->withTrashed()
            ->orderBy('id', 'DESC')
            ->paginate(3);
        } else {
            $coupons = Coupon::select('*')
            // ->where('limit')
            ->where('limit', NULL)
            ->orWhereDate('limit', '>=', now())
            ->orderBy('id', 'DESC')
            ->paginate(3);
        }
        return view('dashboard.coupons.index', compact('coupons', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::select('id', 'title')->get();
        return view('dashboard.coupons.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = [
            'name' => 'required|string',
            'code',
            'discount' => 'required|numeric|min:1',
            'discount_type' => 'required|numeric:between:1,2',
            'product' => 'required_if:discount_type,3,4',
            'valid' => 'required|numeric',
            'limit' => 'date',
            'pay_restrict' => ''
        ];

        $validated = $request->validate(
            $fields,
            [
                'name' => 'Descrição deve ser uma palavra ou frase que descreva o cupom.',
                'discount' => 'Desconto deve ser um número inteiro ou decimal maior que 0.',
                'product' => 'Um produto deve ser selecionado.',
                'discount_type' => 'Porcentagem ou Decimal deve ser escolhido.',
                'valid' => 'Número de cupons disponíveis deve ser -1 ou inteiro maior que 0.',
                'limit' => 'Validade deve ser uma data válida.'
            ]
        );

        // dd($validated);

        $validated['code'] = $request->code ?? strtoupper(Str::random(15));

        $save = Coupon::create($validated);

        if ($save) {
            return redirect(route('coupons.index'))->with('success', 'Cupom criado com sucesso.');
        }

        return redirect()->back()->with('error', 'Houve algum erro ao salvar.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        $products = Product::select('id', 'title')->get();

        return view('dashboard.coupons.edit', compact('coupon', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string',
                'discount' => 'required|numeric|min:1',
                'discount_type' => 'required|numeric:between:1,2',
                'product' => 'required_if:discount_type,3,4',
                'valid' => 'required|numeric',
                'limit' => 'date'
            ],
            [
                'name' => 'Descrição deve ser uma palavra ou frase que descreva o cupom.',
                'discount' => 'Desconto deve ser um número inteiro ou decimal maior que 0.',
                'discount_type' => 'Porcentagem ou Decimal deve ser escolhido.',
                'product' => 'Um produto deve ser selecionado.',
                'valid' => 'Número de cupons disponíveis deve ser -1 ou inteiro maior que 0.',
                'limit' => 'Validade deve ser uma data válida.'
            ]
        );

        $coupon->name = $request->name;
        $coupon->discount = $request->discount;
        $coupon->discount_type = $request->discount_type;
        $coupon->product = $request->product;
        $coupon->valid = $request->valid;
        $coupon->limit = $request->limit;
        $coupon->pay_restrict = $request->pay_restrict;

        if ($coupon->save()) {
            return redirect()->back()->with('success', 'Cupom atualizado com sucesso.');
        }

        return redirect()->back()->with('error', 'Houve algum erro ao atualizar.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        if (!$coupon->delete()) {
            return back()->with('error', 'Erro ao deletar.');
        }
        return back()->with('success', 'Deletado com sucesso.');
    }

    public function restore($coupon)
    {
        if (!Coupon::withTrashed()->find($coupon)->restore()) {
            return back()->with('error', 'Erro ao restaurar.');
        }
        return back()->with('success', 'Restaurado com sucesso.');
    }
}
