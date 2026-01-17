<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $client = User::create([
        //     'name' => 'teste pags',
        //     'doc' => '33430665086',
        //     'email' => 'testepags@teste.com',
        //     'phone' => '5511976338800',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$BhatMESSUkss6nceeH1x1eUgYDD.9pvTS4OQBKRMW7K5q/.ORcBWq',
        // ]);

        // $client->assignRole('cliente');

        // $client = User::create([
        //     'name' => 'Darwin Alencar Schmidt',
        //     'doc' => '43680062087',
        //     'email' => 'darwin.schmidt@uol.com.br',
        //     'phone' => '5511976338800',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$2WuoJCcH7C2Hrkq5IpAMsuf7Na78NFmRt7veq9281yE/ZBdaf4cae',
        // ]);

        // $client->assignRole('cliente');


        $client = User::create([
            'name' => 'José da Silva',
            'doc' => '04130943081',
            'email' => 'joaocmoleta@gmail.com',
            'phone' => '5541999999999',
            'email_verified_at' => now(),
            'password' => '$2y$10$CrzLrB9yzAON4t1uhjbeHOl8XswfbOi/7pHf3Hte63rpJw1b0XbrW', // Cdfaqwlop*#
        ]);

        $client->assignRole('cliente');

        // $client = User::create([
        //     'name' => 'João Moleta',
        //     'doc' => '08201165993',
        //     'email' => 'joaocmoleta@gmail.com',
        //     'phone' => '5541998410336',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$.NqqtewEk26f0GBCFaq4s.Kmy/HPx2BdMAkg4mGWcJY6mewvRGxRq', // ole
        // ]);

        // $client->assignRole('cliente');
    }
}
