<?php

namespace Database\Seeders;

use App\Models\Notes;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Notes::insert([
            [
                'user' => 13,
                'note' => 'Falado com este cliente no dia 10/11/23, ficou de conversar com a sua arquiteta para escolher o modelo',
                'updated_at' => Carbon::now()
            ],
            [
                'user' => 13,
                'note' => 'É possível fazer anotações sobre o LEAD, colocar observações, direcionar as ações encima dele.',
                'updated_at' => Carbon::now()
            ],
            [
                'user' => 13,
                'note' => 'É possível adicionar dados extras sobre as negociações.',
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
