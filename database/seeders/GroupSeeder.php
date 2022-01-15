<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Str;
use Hash;
class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            'name' => 'normal_user',
            'slug' => 'normal_user',
            'description' => 'normal_user',
        ]);
        DB::table('groups')->insert([
            'name' => 'manager',
            'slug' => 'manager',
            'description' => 'manager',
        ]);
        DB::table('groups')->insert([
            'name' => 'general_manager',
            'slug' => 'general_manager',
            'description' => 'general_manager',
        ]);
    }
}
