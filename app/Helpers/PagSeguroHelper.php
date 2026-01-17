<?php

namespace App\Helpers;

class PagSeguroHelper
{
    public static function instance()
    {
        return new PagSeguroHelper();
    }

    public function format_amount($amount)
    {
        // Amount format for 2590.7 should 259070
        $number = number_format($amount, 2, '.', '');
        $result = (int) str_replace('.', '', $number);
        return $result;
    }

    public function amount_to_double($amount) {
        return substr($amount, 0, -2) . '.' . substr($amount, -2);
    }
}