<?php

namespace Database\Seeders;

use App\Models\Action;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Action::insert([
            [
                'action' => 'Verificar como foi a compra',
                'group_name' => 'Conquistar cliente',
                'points' => 100
            ],
            [
                'action' => 'Oferecer desconto para nova compra',
                'group_name' => 'Pós venda',
                'points' => 100
            ],
            [
                'action' => 'Oferecer desconto black friday 2023',
                'group_name' => 'Pós venda',
                'points' => 100
            ],
            [
                'action' => 'Indique um amigo e ganhe',
                'group_name' => 'Pós venda',
                'points' => 100
            ],
            [
                'action' => 'Desconto para retomar compra',
                'group_name' => 'Conquistar cliente',
                'points' => 100
            ],
        ]);
    }
}
