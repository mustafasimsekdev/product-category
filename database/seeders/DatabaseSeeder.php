<?php

namespace Database\Seeders;

use App\Models\Logs;
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
        $this->call([
            UserSeeder::class,
            Logs::class,
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
