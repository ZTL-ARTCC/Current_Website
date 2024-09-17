<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

return new class extends Seeder {
    /**
     * Run the database seeders.
     *
     */
    public function run(): void {
        DB::table('live_event')->insert([
            [
                'event_title' => 'Event Name Here',
                'body_public' => 'Insert text that you want the public to see here',
                'body_private' => 'Insert text that you want authenticated users to see here',
                'staff_member' => 0
            ]
        ]);
    }
};
