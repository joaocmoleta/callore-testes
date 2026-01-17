<?php

namespace Database\Seeders;

use App\Models\Encomenda;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EncomendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $volumes = [
            [
                'awb' => 'TXAU339213589tx',
                'rota' => '(A)02-BHI-MG-ECT-[ECT]',
                'codigoBarras' => 'TXAU339213589tx',
            ]
        ];

        Encomenda::create([
            'order' => 5,
            'pedido' => 'Teste-1234',
            'cliente_codigo' => 'flafalj',
            'tipo_servico' => '',
            'data' => '2023-10-04',
            'hora' => '14:29:07',
            'volumes' => json_encode($volumes)
        ]);
    }
}
