<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('devices')->insert([
            [
                'name' => 'monitoring'
            ],
            [
                'name' => 'motion'
            ],
            [
                'name' => 'camera'
            ],
            [
                'name' => 'agenda'
            ],
            [
                'name' => 'sensor'
            ],
            [
                'name' => 'door'
            ]
        ]);
    }
}
