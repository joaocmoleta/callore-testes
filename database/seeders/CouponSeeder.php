<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coupon::create([
            'name' => 'Condomínio Xangrilá',
            'code' => 'XDGXQAR4JQWLVOF',
            'discount' => 10,
            'discount_type' => 1,
            'valid' => -1,
        ]);

        // Coupon::create([
        //     'name' => '100 reais de desconto',
        //     // 'code' => strtoupper(Str::random(15)),
        //     'code' => 'HXQCHLAFD26QSDQ',
        //     'discount' => 100,
        //     'discount_type' => 4,
        //     'product' => 1,
        //     'valid' => -1,
        // ]);
    }
}
