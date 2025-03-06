<?php

namespace Database\Seeders;

use App\Models\ServerStatus;
use Illuminate\Database\Seeder;

class ServerStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serverStatus = ServerStatus::updateOrCreate(['id' => 1], ['id' => 1]);
    }
}
