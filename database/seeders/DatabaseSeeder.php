<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ActionSeeder::class,
            DeviceSeeder::class,
            ActorTypeSeeder::class,
            EventSeeder::class,
            UserSeeder::class,
        ]);
    }
}
