<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::create([
            'products' => '[{"id":"1","name":"Aquecedor Callore Vers\u00e1til Bege","slug":"aquecedor-callore-versatil-bege","qtd":"1","value_uni":1040,"subtotal":1040}]',
            'user' => 2,
            'status' => 'new',
            'payment' => 1,
        ]);

        Payment::create([
            'cd_holder_name' => 'João Moleta'
        ]);
    }
}
