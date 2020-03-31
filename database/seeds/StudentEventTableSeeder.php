<?php

use Illuminate\Database\Seeder;

class StudentEventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('student_event')->insert([
            'student_id' => 3,
            'event_id' => 1,
        ]);
        DB::table('student_event')->insert([
            'student_id' => 3,
            'event_id' => 2,
        ]);
    }
}
