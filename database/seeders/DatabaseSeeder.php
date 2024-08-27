<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory(1)->create([
            'phone' => '998901234567',
        ]);
        User::factory(1)->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'phone' => '998912345678',
        ]);
        Order::factory(10)->create([
            'user_id' => $user[0]->id,
        ]);
    }
}
