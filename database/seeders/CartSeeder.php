<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cart::create([
            'user' => 1,
            'products' => '[{"product":1,"qtd":1},{"product":2,"qtd":2},{"product":3,"qtd":1}]',
            'qtd' => 4,
            'amount' => 4000.33,
        ]);

        Cart::create([
            'user' => 2,
            'products' => '[{"product":1,"qtd":1},{"product":2,"qtd":2},{"product":3,"qtd":1}]',
            'qtd' => 4,
            'amount' => 4000.33,
        ]);
    }
}
