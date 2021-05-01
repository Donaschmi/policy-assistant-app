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
                'sentence' => 'votre fils',
                'privacy' => 3
            ],
            [
                'name' => 'fils_2',
                'intent' => 'AnswerSon',
                'sentence' => 'votre fils',
                'privacy' => 3
            ],
            [
                'name' => 'fils_3',
                'intent' => 'AnswerSon',
                'sentence' => 'votre fils',
                'privacy' => 3
            ],
            [
                'name' => 'fille_1',
                'intent' => 'AnswerDaughter',
                'sentence' => 'votre fille',
                'privacy' => 3
            ],
            [
                'name' => 'fille_2',
                'intent' => 'AnswerDaughter',
                'sentence' => 'votre fille',
                'privacy' => 3
            ],
            [
                'name' => 'fille_3',
                'intent' => 'AnswerDaughter',
                'sentence' => 'votre fille',
                'privacy' => 3
            ],
            [
                'name' => 'voisin_1',
                'intent' => 'AnswerNeighbour',
                'sentence' => 'votre voisin',
                'privacy' => 0
            ],
            [
                'name' => 'voisin_2',
                'intent' => 'AnswerNeighbour',
                'sentence' => 'votre voisin',
                'privacy' => 0
            ],
            [
                'name' => 'voisin_3',
                'intent' => 'AnswerNeighbour',
                'sentence' => 'votre voisin',
                'privacy' => 0
            ],
            [
                'name' => 'police',
                'intent' => 'AnswerPolice',
                'sentence' => 'la police',
                'privacy' => 2
            ],
            [
                'name' => 'pompier',
                'intent' => 'AnswerFirefighter',
                'sentence' => 'les pompiers',
                'privacy' => 2
            ],
            [
                'name' => 'ambulance',
                'intent' => 'AnswerAmbulance',
                'sentence' => 'l\'hôpital',
                'privacy' => 3
            ],
            [
                'name' => 'carer',
                'intent' => 'AnswerCarer',
                'sentence' => 'votre aide-soignant',
                'privacy' => 3
            ],
        ]);
    }
}
