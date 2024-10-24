<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PilotPassportSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('pilot_passport')->insert([
            'id' => 1,
            'title' => 'Airline Captain',
            'description' => 'The airline path features airports served by part 121 commercial carriers.'
         ]);
        DB::table('pilot_passport')->insert([
           'id' => 2,
           'title' => 'Corporate Pilot',
           'description' => 'The business aviator path features airports commonly frequented by part 135 operators flying private jets and turboprops.'
        ]);
        DB::table('pilot_passport')->insert([
            'id' => 3,
            'title' => 'Bug Smasher',
            'description' => 'The bug smasher path features the many smaller airports throughout Georgia, Alabama, South Carolina, North Carolina, and Tennessee.'
        ]);
    }
}
