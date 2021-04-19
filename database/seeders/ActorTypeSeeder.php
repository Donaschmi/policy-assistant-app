<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('actor_types')->insert([
            [
                'name' => 'fils_1',
                'sentence' => 'votre fils'
            ],
            [
                'name' => 'fils_2',
                'sentence' => 'votre fils'
            ],
            [
                'name' => 'fils_3',
                'sentence' => 'votre fils'
            ],
            [
                'name' => 'fille_1',
                'sentence' => 'votre fille'
            ],
            [
                'name' => 'fille_2',
                'sentence' => 'votre fille'
            ],
            [
                'name' => 'fille_3',
                'sentence' => 'votre fille'
            ],
            [
                'name' => 'voisin_1',
                'sentence' => 'votre voisin'
            ],
            [
                'name' => 'voisin_2',
                'sentence' => 'votre voisin'
            ],
            [
                'name' => 'voisin_3',
                'sentence' => 'votre voisin'
            ],
            [
                'name' => 'police',
                'sentence' => 'la police'
            ],
            [
                'name' => 'pompier',
                'sentence' => 'les pompiers'
            ],
            [
                'name' => 'ambulance',
                'sentence' => 'l\'hôpital'
            ],
        ]);
    }
}
