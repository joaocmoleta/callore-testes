<?php

namespace Database\Seeders;

use App\Models\UserAction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserAction::insert([
            [
                'user' => 10,
                'action' => 1,
                'status' => 0,
            ],
            [
                'user' => 10,
                'action' => 2,
                'status' => 1,
            ],
            [
                'user' => 13,
                'action' => 1,
                'status' => 1,
            ]
            ]);
    }
}
