<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::updateOrCreate(['email' => 'c01@bk.ru'], [
            'name' => 'c01',
            'email_verified_at' => now(),
            'password' => bcrypt('000000')
        ]);
    }
}
