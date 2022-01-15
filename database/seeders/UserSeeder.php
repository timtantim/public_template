<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Str;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 'name' => Str::random(10),
        // 'email' => Str::random(10).'admin@gmail.com',
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('88888'),
            'email_verified_at'=>'2021-12-07 12:19:12'
        ]);
    }
}
