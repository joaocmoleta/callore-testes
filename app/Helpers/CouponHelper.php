<?php

namespace App\Helpers;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class CouponHelper
{
    public static function instance()
    {
        return new CouponHelper();
    }

    public function get($code)
    {
        // Just return if limit or limit == null and valid != 0
        return Coupon::where('code', $code)
            ->whereNot('valid', 0)
            ->where(function ($query) {
                $query->where('limit', '>=', now())
                    ->orWhere('limit', null);
            })
            ->first();
    }

    /**
     * Pegar amount, discount e coupon
     * 
     * @param string $code Código do cupom
     * @param object $products Lista de produtos na sacola
     * @param boolean $conf Utilizado para testes
     * @return array Retorna amount, discoun e coupon
     */
    // Adicionar um argumento na função que o padrão não altere a implementação,
    // mas que permita adicionar uma listagem apenas de conferência nos pedidos
    public function get_amount($code, $products, $conf = false, $pay_restrict = false)
    {
        if ($conf) { // Não valida o cupon apenas o aplica a lista de teste
            $coupon = Coupon::where('code', $code)
                ->first();
        } else {
            $coupon = $this->searchCoupon($code, $pay_restrict);
        }

        $search = $this->searchProductDiscountAndAmount($products, $coupon);
        $amount = $search[0];
        $product_is_discount = $search[1];

        if (!$coupon) {
            return ['amount' => $amount, 'discount' => 0, 'coupon' => $coupon];
        }

        $discount = 0;

        if ($coupon->discount_type == 1) {
            $discount = $amount * ($coupon->discount / 100);
            $amount = $amount - $discount;
        }

        if ($coupon->discount_type == 2) {
            $discount = $coupon->discount;
            $amount = $amount - $discount;
        }

        if ($coupon->discount_type == 3 && $product_is_discount) {
            $discount = $product_is_discount->value * ($coupon->discount / 100);
            $product_value_with_discount = $product_is_discount->value - $product_is_discount->value * ($coupon->discount / 100);
            $amount -= $product_is_discount->value;
            $amount += $product_value_with_discount;
        }

        if ($coupon->discount_type == 4 && $product_is_discount) {
            $discount = $coupon->discount;
            $product_value_with_discount = $product_is_discount->value - $coupon->discount;
            $amount -= $product_is_discount->value;
            $amount += $product_value_with_discount;
        }

        $amount = number_format($amount, 2, '.', '');

        return ['amount' => $amount, 'discount' => $discount, 'coupon' => $coupon];
    }

    /**
     * Busca no banco de dados cupom pelo código
     * Verifica se não está expirado. Para cupons com prazo de validade
     * Se tem cupons disponíveis ou esgotaram. Para cupons limitados
     * 
     * @param $code Código do cupom
     * @return object
     */
    public function searchCoupon($code, $pay_restrict)
    {
        $coupon = Coupon::where('code', $code)
            ->whereNot('valid', 0)
            ->where(function ($query) {
                $query->where('limit', '>=', now())
                    ->orWhere('limit', null);
            })
            // Busca se o cupom tiver o meio de pgt ou vazio, caso não seja restrito
            ->where(function ($query) use ($pay_restrict) {
                $query->where('pay_restrict', $pay_restrict)
                    ->orWhere('pay_restrict', null);
            });

        return $coupon->first();
    }

    /**
     * Vasculha os produtos do carrinho para somar o amount
     * e identificar o produto com desconto, se está na lista
     * 
     * @param $products
     * @param $coupon
     * @return array Retorna object|null com id e valor do produto com desconto
     */
    public function searchProductDiscountAndAmount($products, $coupon)
    {
        $amount = 0;
        $product_is_discount = false;
        foreach ($products as $product) {
            $db_product = Product::select('id', 'value')
                ->where('id', $product->id)
                ->first();

            $amount += $db_product->value * $product->qtd;

            if ($coupon && $product->id == $coupon->product) {
                $product_is_discount = $db_product;
            }
        }
        return [
            $amount,
            $product_is_discount
        ];
    }
}
