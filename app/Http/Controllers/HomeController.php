<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Testimony;
use App\Models\User;
use App\Models\Variation;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $cart = \App\Helpers\CartHelper::instance()->check_cart();
        $products = Product::select(
            '*'
        )->limit(4)->get();

        $testimonies = Testimony::select('*')->get();

        return view('home', compact('products', 'cart', 'testimonies'));
    }

    public function homeVideo()
    {
        $cart = \App\Helpers\CartHelper::instance()->check_cart();
        $products = Product::select(
            '*'
        )->limit(4)->get();
        // $products = Product::select('*')->limit(4)->get();
        $testimonies = Testimony::select('*')->get();
        return view('home-summer', compact('products', 'cart', 'testimonies'));
    }

    public function unsubscribe($email)
    {
        $finded = User::select('id')->where('email', $email)->first();

        if ($finded) {
            // remover e retornar view
            $finded->update([
                    'publicidade' => 0
                ]);

            return view('user.unsubscribe');
        } else {
            // retornar view de erro
            return view('user.error');
        }
    }
}
