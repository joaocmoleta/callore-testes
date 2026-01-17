<?php

namespace App\Helpers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CartHelper
{
    public static function instance()
    {
        return new CartHelper();
    }

    public function check_cart()
    {
        $user_logged = auth()->user();

        if ($user_logged && session()->has('cart_session')) {
            $cart_session = session()->get('cart_session');
            $cart_by_session = Cart::where('cart_session', $cart_session)->first();
            if ($cart_by_session) {
                $cart_by_session->user = $user_logged->id;
                $cart_by_session->save();
            } else {
                return null;
            }

            return $cart_by_session;
        }

        if ($user_logged) {
            return Cart::where('user', $user_logged->id)->first();
        }

        if (session()->has('cart_session')) {
            $cart_session = session()->get('cart_session');
            return Cart::where('cart_session', $cart_session)->first();
        }

        return null;
    }

    public function create_cart($new_cart)
    {
        $cart_session = Str::random(15);
        session()->put('cart_session', $cart_session);
        session()->save();

        $new_cart['cart_session'] = $cart_session;

        if (!Cart::create($new_cart)) {
            return false;
        }

        return true;
    }

    public function update_cart($product_adding, $cart) // [$product, $req->qtd], $cart)
    {
        $current_products = json_decode($cart->products);

        $new_products = [];
        $amount = 0;
        $qtd_geral = 0;
        $add = true;
        foreach ($current_products as $item) {
            $qtd = 0;
            if ($item->product == $product_adding[0]) {
                $qtd = $item->qtd + $product_adding[1];
                $add = false;
            } else {
                $qtd = $item->qtd;
            }

            $new_products[] = [
                'product' => $item->product,
                'qtd' => $qtd,
                'value_uni' => $item->value_uni,
            ];

            $amount += $item->value_uni * $qtd;
            $qtd_geral += $qtd;
        }

        if ($add) {
            $db_product = Product::find($product_adding[0]);
            $new_products[] = [
                'product' => $product_adding[0],
                'qtd' => $product_adding[1],
                'value_uni' => $db_product->value,
            ];
            $qtd_geral += $product_adding[1];
            $amount += $db_product->value * $product_adding[1];
        }

        $cart->products = json_encode($new_products);
        $cart->amount = $amount;
        $cart->qtd = $qtd_geral;


        if (!$cart->save()) {
            return false;
        }

        return true;
    }

    public function remove_item($product_id, $cart) {
        Log::info($product_id);
        Log::info(json_encode($cart));

        $products_cart = json_decode($cart->products);

        $new_products = [];
        $amount = 0;
        $qtd_geral = 0;

        foreach($products_cart as $product) {
            if($product->product != $product_id) {
                $new_products[] = [
                    'product' => $product->product,
                    'qtd' => $product->qtd,
                    'value_uni' => $product->value_uni,
                ];
    
                $amount += $product->value_uni * $product->qtd;
                $qtd_geral += $product->qtd;
            }
        }

        $cart->products = json_encode($new_products);
        $cart->amount = $amount;
        $cart->qtd = $qtd_geral;

        if (!$cart->save()) {
            return false;
        }

        return true;
    }

    public function remove_cart()
    {
        Log::info(static::class . ' linha ' . __LINE__ . ' Iniciando remoção de carrinho');
        if (session()->has('cart_session')) {
            $cart_session = session()->get('cart_session');

            $cart = Cart::where('cart_session', $cart_session)->first();
            if ($cart) {
                $del_cart = Cart::where('cart_session', $cart_session)->delete();

                Log::info(static::class . ' linha ' . __LINE__ . ' Del cart db ');
                Log::info($del_cart);
                
                // $del = $cart->delete();
                session()->forget('cart_session');
                return true;
            }
        }

        return null;
    }

    public function add_coupon($coupon)
    {
        if (session()->has('cart_session')) {
            $cart_session = session()->get('cart_session');
            $cart = Cart::where('cart_session', $cart_session)->first();
            $cart->coupon = $coupon;
            $cart->save();
        }
    }
}
