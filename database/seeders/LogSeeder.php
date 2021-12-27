<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('logs')->delete();
        DB::table('logs')->insert([
            0 =>   [
                'log' => 'Eklendi',
                'created_at' => Carbon::now(),
            ],
            1 =>   [
                'log' => 'Guncelledi',
                'created_at' => Carbon::now(),
            ],
            2 =>   [
                'log' => 'Sildi',
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
