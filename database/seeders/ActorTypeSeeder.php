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
                'intent' => 'AnswerSon',
                'sentence' => 'votre fils'
            ],
            [
                'name' => 'fils_2',
                'intent' => 'AnswerSon',
                'sentence' => 'votre fils'
            ],
            [
                'name' => 'fils_3',
                'intent' => 'AnswerSon',
                'sentence' => 'votre fils'
            ],
            [
                'name' => 'fille_1',
                'intent' => 'AnswerDaughter',
                'sentence' => 'votre fille'
            ],
            [
                'name' => 'fille_2',
                'intent' => 'AnswerDaughter',
                'sentence' => 'votre fille'
            ],
            [
                'name' => 'fille_3',
                'intent' => 'AnswerDaughter',
                'sentence' => 'votre fille'
            ],
            [
                'name' => 'voisin_1',
                'intent' => 'AnswerNeighbour',
                'sentence' => 'votre voisin'
            ],
            [
                'name' => 'voisin_2',
                'intent' => 'AnswerNeighbour',
                'sentence' => 'votre voisin'
            ],
            [
                'name' => 'voisin_3',
                'intent' => 'AnswerNeighbour',
                'sentence' => 'votre voisin'
            ],
            [
                'name' => 'police',
                'intent' => 'AnswerPolice',
                'sentence' => 'la police'
            ],
            [
                'name' => 'pompier',
                'intent' => 'AnswerFirefighter',
                'sentence' => 'les pompiers'
            ],
            [
                'name' => 'ambulance',
                'intent' => 'AnswerAmbulance',
                'sentence' => 'l\'hôpital'
            ],
            [
                'name' => 'carer',
                'intent' => 'AnswerCarer',
                'sentence' => 'votre aide-soignant'
            ],
        ]);
    }
}
