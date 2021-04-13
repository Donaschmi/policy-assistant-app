<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('actions')->insert([
            [
                'name' => 'call'
            ],
            [
                'name' => 'notification'
            ],
            [
                'name' => 'message'
            ]
        ]);
    }
}
