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
                'name' => 'call',
                'sentence' => 'appeler'
            ],
            [
                'name' => 'notification',
                'sentence' => 'notifier'
            ],
            [
                'name' => 'message',
                'sentence' => 'envoyer un sms à'
            ]
        ]);
    }
}
