<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Artisan;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            GroupSeeder::class,
            PermissionsSeeder::class,
            UserSeeder::class,
            UserHasPermissionSeeder::class,
            UserHasGroupSeeder::class,
            CurrencySeeder::class
        ]);
        // Artisan::call('passport:client --personal --name=custom_token');
    }
}
