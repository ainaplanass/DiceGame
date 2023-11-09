<?php

namespace Database\Seeders;

 use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $user =  \App\Models\User::factory()->create([
             'nickname' => 'Aina',
             'email' => 'aina@example.com',
             'password' => 'que',
         ]);
         $user->assignRole('admin');
    }
}
