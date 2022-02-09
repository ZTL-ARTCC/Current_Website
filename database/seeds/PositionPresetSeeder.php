<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionPresetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = Carbon::now();
        DB::table('preset_positions')->insert([
             'name' => 'default',
             'created_at' => $time,
             'updated_at' => $time
         ]);
    }
}
