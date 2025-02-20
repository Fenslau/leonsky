<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(['email' => 'c01@bk.ru'], [
            'name' => 'c01',
            'email_verified_at' => now(),
            'password' => Hash::make('000000')
        ]);
        $user->profile()->updateOrCreate([
            'role' => UserRoleEnum::ADMIN,
            'region_id' => 1070,
            'city_id' => 26405
        ]);

        $user = User::updateOrCreate(['email' => 'admin@leone-sky.ru'], [
            'name' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('000000')
        ]);
        $user->profile()->updateOrCreate([
            'role' => UserRoleEnum::ADMIN,
            'region_id' => 1070,
            'city_id' => 26405
        ]);

        $user = User::updateOrCreate(['email' => 'support@leone-sky.ru'], [
            'name' => 'support',
            'email_verified_at' => now(),
            'password' => Hash::make('000000')
        ]);
        $user->profile()->updateOrCreate([
            'role' => UserRoleEnum::ADMIN,
            'region_id' => 1070,
            'city_id' => 26405
        ]);
    }
}
