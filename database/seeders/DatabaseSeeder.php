<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'saitroan',
            'email' => 'paul@example.com',
        ]);

        User::factory()->create([
            'name' => 'oralell',
            'email' => 'ella@example.com',
        ]);

        User::factory()->create([
            'name' => 'drshinra',
            'email' => 'paulo@example.com',
        ]);
    }
}
