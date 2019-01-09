<?php

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OverflightUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('flights_within_artcc_updates')->insert([
             'created_at' => null,
             'updated_at' => null
         ]);
    }
}
