<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('actors')->insert([
            [
                'name' => 'fils_1'
            ],
            [
                'name' => 'fils_2'
            ],
            [
                'name' => 'fils_3'
            ],
            [
                'name' => 'fille_1'
            ],
            [
                'name' => 'fille_2'
            ],
            [
                'name' => 'fille_3'
            ],
            [
                'name' => 'voisin_1'
            ],
            [
                'name' => 'voisin_2'
            ],
            [
                'name' => 'voisin_3'
            ],
            [
                'name' => 'police'
            ],
            [
                'name' => 'pompier'
            ],
            [
                'name' => 'ambulance'
            ],
        ]);
    }
}
