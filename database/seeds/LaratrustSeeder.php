<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		// Adds Roles to the Roles Table
        DB::table('roles')->insert([
             'name' => 'atm',
             'display_name' => 'Air Traffic Manager'
        ]);
        DB::table('roles')->insert([
             'name' => 'datm',
             'display_name' => 'Deputy Air Traffic Manager'
        ]);
		DB::table('roles')->insert([
             'name' => 'ta',
             'display_name' => 'Training Administrator'
        ]);
		DB::table('roles')->insert([
             'name' => 'ata',
             'display_name' => 'Assistant Training Administrator'
        ]);
		DB::table('roles')->insert([
             'name' => 'wm',
             'display_name' => 'Webmaster'
        ]);
		DB::table('roles')->insert([
             'name' => 'awm',
             'display_name' => 'Assistant Webmaster'
        ]);
		DB::table('roles')->insert([
             'name' => 'ec',
             'display_name' => 'Events Coordinator'
        ]);
		DB::table('roles')->insert([
             'name' => 'aec',
             'display_name' => 'Assistant Events Coordinator'
        ]);
		DB::table('roles')->insert([
             'name' => 'fe',
             'display_name' => 'Assistant Facility Engineer'
        ]);
		DB::table('roles')->insert([
             'name' => 'afe',
             'display_name' => 'Assistant Facility Engineer'
        ]);
		DB::table('roles')->insert([
             'name' => 'mtr',
             'display_name' => 'Mentor'
        ]);
		DB::table('roles')->insert([
             'name' => 'ins',
             'display_name' => 'Instructor'
        ]);
		
		// Adds Permissions to the Permissions Table
        DB::table('permissions')->insert([
             'name' => 'snrStaff',
             'display_name' => 'ATM, DATM, TA, ATA, WM, AWM',
			 'description' => ''
        ]);
		DB::table('permissions')->insert([
             'name' => 'staff',
             'display_name' => 'Staff',
			 'description' => 'snrStaff + EC, AEC, FE, AFE'
        ]);
		DB::table('permissions')->insert([
             'name' => 'ins',
             'display_name' => 'Instructors',
			 'description' => 'Instructors'
        ]);
		DB::table('permissions')->insert([
             'name' => 'mtr',
             'display_name' => 'Mentors',
			 'description' => 'Mentors'
        ]);
		DB::table('permissions')->insert([
             'name' => 'train',
             'display_name' => 'Trainers',
			 'description' => 'Mentors + Instructors'
        ]);
		DB::table('permissions')->insert([
             'name' => 'scenery',
             'display_name' => 'Scenery'
        ]);
		DB::table('permissions')->insert([
             'name' => 'files',
             'display_name' => 'Files'
        ]);
		DB::table('permissions')->insert([
             'name' => 'roster',
             'display_name' => 'Roster'
        ]);
		DB::table('permissions')->insert([
             'name' => 'events',
             'display_name' => 'Events'
        ]);
		DB::table('permissions')->insert([
             'name' => 'changeRating',
             'display_name' => 'Change Rating'
        ]);
		DB::table('permissions')->insert([
             'name' => 'changeCert',
             'display_name' => 'Change Certification'
        ]);
		DB::table('permissions')->insert([
             'name' => 'email',
             'display_name' => 'Email'
        ]);
		
		
		
		//Links Roles and Permissions
		DB::table('permission_role')->insert([
			'permission_id' => 1,
			'role_id' => 1
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 2,
			'role_id' => 1
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 3,
			'role_id' => 1
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 4,
			'role_id' => 1
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 5,
			'role_id' => 1
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 6,
			'role_id' => 1
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 7,
			'role_id' => 1
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 8,
			'role_id' => 1
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 9,
			'role_id' => 1
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 10,
			'role_id' => 1
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 11,
			'role_id' => 1
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 12,
			'role_id' => 1
		]);
		
		DB::table('permission_role')->insert([
			'permission_id' => 1,
			'role_id' => 2
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 2,
			'role_id' => 2
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 3,
			'role_id' => 2
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 4,
			'role_id' => 2
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 5,
			'role_id' => 2
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 6,
			'role_id' => 2
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 7,
			'role_id' => 2
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 8,
			'role_id' => 2
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 9,
			'role_id' => 2
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 10,
			'role_id' => 2
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 11,
			'role_id' => 2
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 12,
			'role_id' => 2
		]);
		
		DB::table('permission_role')->insert([
			'permission_id' => 1,
			'role_id' => 3
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 2,
			'role_id' => 3
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 3,
			'role_id' => 3
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 4,
			'role_id' => 3
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 5,
			'role_id' => 3
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 6,
			'role_id' => 3
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 7,
			'role_id' => 3
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 8,
			'role_id' => 3
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 9,
			'role_id' => 3
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 10,
			'role_id' => 3
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 11,
			'role_id' => 3
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 12,
			'role_id' => 3
		]);
		
		DB::table('permission_role')->insert([
			'permission_id' => 1,
			'role_id' => 4
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 2,
			'role_id' => 4
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 3,
			'role_id' => 4
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 4,
			'role_id' => 4
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 5,
			'role_id' => 4
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 6,
			'role_id' => 4
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 7,
			'role_id' => 4
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 9,
			'role_id' => 4
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 10,
			'role_id' => 4
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 11,
			'role_id' => 4
		]);
		
		DB::table('permission_role')->insert([
			'permission_id' => 1,
			'role_id' => 5
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 2,
			'role_id' => 5
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 3,
			'role_id' => 5
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 4,
			'role_id' => 5
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 5,
			'role_id' => 5
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 6,
			'role_id' => 5
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 7,
			'role_id' => 5
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 8,
			'role_id' => 5
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 9,
			'role_id' => 5
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 10,
			'role_id' => 5
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 11,
			'role_id' => 5
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 12,
			'role_id' => 5
		]);
		
		DB::table('permission_role')->insert([
			'permission_id' => 1,
			'role_id' => 6
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 2,
			'role_id' => 6
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 3,
			'role_id' => 6
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 4,
			'role_id' => 6
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 5,
			'role_id' => 6
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 6,
			'role_id' => 6
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 7,
			'role_id' => 6
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 8,
			'role_id' => 6
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 9,
			'role_id' => 6
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 10,
			'role_id' => 6
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 11,
			'role_id' => 6
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 12,
			'role_id' => 6
		]);
		
		DB::table('permission_role')->insert([
			'permission_id' => 2,
			'role_id' => 7
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 9,
			'role_id' => 7
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 12,
			'role_id' => 7
		]);
		
		DB::table('permission_role')->insert([
			'permission_id' => 2,
			'role_id' => 8
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 9,
			'role_id' => 8
		]);
		
		DB::table('permission_role')->insert([
			'permission_id' => 6,
			'role_id' => 9
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 7,
			'role_id' => 9
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 12,
			'role_id' => 9
		]);
		
		DB::table('permission_role')->insert([
			'permission_id' => 6,
			'role_id' => 10
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 7,
			'role_id' => 10
		]);
		
		DB::table('permission_role')->insert([
			'permission_id' => 5,
			'role_id' => 11
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 11,
			'role_id' => 11
		]);
		
		DB::table('permission_role')->insert([
			'permission_id' => 5,
			'role_id' => 12
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 10,
			'role_id' => 12
		]);
		DB::table('permission_role')->insert([
			'permission_id' => 11,
			'role_id' => 12
		]);
    }
}
