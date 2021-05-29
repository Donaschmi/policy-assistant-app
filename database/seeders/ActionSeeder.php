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
                'sentence' => 'appeler',
                'reactivity' => 3
            ],
            [
                'name' => 'notification',
                'sentence' => 'notifier',
                'reactivity' => 1
            ],
            [
                'name' => 'message',
                'sentence' => 'envoyer un sms à',
                'reactivity' => 2
            ]
        ]);
    }
}
