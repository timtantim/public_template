<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            'source_currency' => 'TWD',
            'target_currency' => 'TWD',
            'exchange_rate' => '1',
        ]);
        DB::table('currencies')->insert([
            'source_currency' => 'TWD',
            'target_currency' => 'JPY',
            'exchange_rate' => '3.669',
        ]);
        DB::table('currencies')->insert([
            'source_currency' => 'TWD',
            'target_currency' => 'USD',
            'exchange_rate' => '0.03281',
        ]);

        DB::table('currencies')->insert([
            'source_currency' => 'JPY',
            'target_currency' => 'TWD',
            'exchange_rate' => '0.26956',
        ]);
        DB::table('currencies')->insert([
            'source_currency' => 'JPY',
            'target_currency' => 'JPY',
            'exchange_rate' => '1',
        ]);
        DB::table('currencies')->insert([
            'source_currency' => 'JPY',
            'target_currency' => 'USD',
            'exchange_rate' => '0.00885',
        ]);

        DB::table('currencies')->insert([
            'source_currency' => 'USD',
            'target_currency' => 'TWD',
            'exchange_rate' => '30.444',
        ]);
        DB::table('currencies')->insert([
            'source_currency' => 'USD',
            'target_currency' => 'JPY',
            'exchange_rate' => '111.801',
        ]);
        DB::table('currencies')->insert([
            'source_currency' => 'USD',
            'target_currency' => 'USD',
            'exchange_rate' => '1',
        ]);
    }
}
