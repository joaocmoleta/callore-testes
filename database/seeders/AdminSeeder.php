<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Desenvolvimento',
            'email' => 'contato@moleta.com.br',
            'email_verified_at' => now(),
            'password' => '$2y$10$.NqqtewEk26f0GBCFaq4s.Kmy/HPx2BdMAkg4mGWcJY6mewvRGxRq', // ole
        ]);

        $user->assignRole('super', 'admin');

        $user = User::create([
            'name' => 'Administração',
            'email' => 'marketing@moleta.com.br',
            'email_verified_at' => now(),
            'password' => '$2y$10$.NqqtewEk26f0GBCFaq4s.Kmy/HPx2BdMAkg4mGWcJY6mewvRGxRq', // ole
        ]);

        $user->assignRole('admin');

        // $admin1 = User::create([
        //     'name' => 'Jeferson Pimentel',
        //     'email' => 'jeferson@quebecsemfronteiras.com.br',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$FEjw2hJkI0Ve1UbPggk/aOKGQQdq6s3ieE5ZIlxvKzr6tf.uALYaa', // C@nada1497
        // ]);

        // $admin1->assignRole('admin');

        // $admin2 = User::create([
        //     'name' => 'Secretaria QSF',
        //     'email' => 'secretaria@quebecsemfronteiras.com.br',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$FEjw2hJkI0Ve1UbPggk/aOKGQQdq6s3ieE5ZIlxvKzr6tf.uALYaa', // C@nada1497
        // ]);

        // $admin2->assignRole('admin');
    }
}
