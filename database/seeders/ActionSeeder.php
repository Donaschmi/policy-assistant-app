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
                'assignable' => true,
                'sentence' => 'appeler'
            ],
            [
                'name' => 'notification',
                'assignable' => true,
                'sentence' => 'notifier'
            ],
            [
                'name' => 'message',
                'assignable' => true,
                'sentence' => 'envoyer un sms à'
            ]
        ]);

        DB::table('actions')->insert([
            [
                'name' => 'turn on light',
                'assignable' => false,
                'sentence' => 'allumer la lumière'
            ],[
                'name' => 'turn off light',
                'assignable' => false,
                'sentence' => 'éteindre la lumière'
            ],[
                'name' => 'turn off coffee machine',
                'assignable' => false,
                'sentence' => 'éteindre la machine à café'
            ],[
                'name' => 'turn off heater',
                'assignable' => false,
                'sentence' => 'éteindre le chauffage'
            ],
        ]);
    }
}
