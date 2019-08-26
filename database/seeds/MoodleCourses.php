<?php

use Illuminate\Database\Seeder;

class MoodleCourses extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('moodle_course_assignments')->insert([
            'course_name' => 'Intro',
            'course_id' => 2,
            'rating_id' => 0,
            'isVisitor' => 1,
            'mdl_enrol_id' => 50
        ]);

        DB::table('moodle_course_assignments')->insert([
            'course_name' => 'S1',
            'course_id' => 12,
            'rating_id' => 1,
            'isVisitor' => 0,
            'mdl_enrol_id' => 51
        ]);

        DB::table('moodle_course_assignments')->insert([
            'course_name' => 'S2',
            'course_id' => 4,
            'rating_id' => 2,
            'isVisitor' => 0,
            'mdl_enrol_id' => 52
        ]);

        DB::table('moodle_course_assignments')->insert([
            'course_name' => 'CLT',
            'course_id' => 10,
            'rating_id' => 2,
            'isVisitor' => 0,
            'mdl_enrol_id' => 53
        ]);

        DB::table('moodle_course_assignments')->insert([
            'course_name' => 'KATL',
            'course_id' => 6,
            'rating_id' => 2,
            'isVisitor' => 1,
            'mdl_enrol_id' => 54
        ]);

        DB::table('moodle_course_assignments')->insert([
            'course_name' => 'S3',
            'course_id' => 5,
            'rating_id' => 3,
            'isVisitor' => 0,
            'mdl_enrol_id' => 55
        ]);

        DB::table('moodle_course_assignments')->insert([
            'course_name' => 'A80',
            'course_id' => 7,
            'rating_id' => 3,
            'isVisitor' => 1,
            'mdl_enrol_id' => 56
        ]);

        DB::table('moodle_course_assignments')->insert([
            'course_name' => 'KZTL',
            'course_id' => 8,
            'rating_id' => 4,
            'isVisitor' => 1,
            'mdl_enrol_id' => 57
        ]);

        DB::table('moodle_course_assignments')->insert([
            'course_name' => '$$$',
            'course_id' => 9,
            'rating_id' => 5,
            'isVisitor' => 0,
            'mdl_enrol_id' => 58
        ]);
    }
}
