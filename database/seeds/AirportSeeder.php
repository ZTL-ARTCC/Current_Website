<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//This will show first on the website homepage
        DB::table('airports')->insert([
             'name' => 'Atlanta International',
             'ltr_4' => 'KATL',
             'ltr_3' => 'ATL'
         ]);

		//This will show second on the website homepage
        DB::table('airports')->insert([
             'name' => 'Charlotte International',
             'ltr_4' => 'KCLT',
             'ltr_3' => 'CLT'
         ]);

	    //This will show third on the website homepage
        DB::table('airports')->insert([
             'name' => 'Birmingham International',
             'ltr_4' => 'KBHM',
             'ltr_3' => 'BHM'
         ]);
    }
}
