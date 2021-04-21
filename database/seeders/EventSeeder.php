<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->insert([
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'heartbeat',
                'operator' => '>',
                'value' => '130',
                'sentence' => 'votre rythme cardiaque augmente anormalement'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'heartbeat',
                'operator' => '<',
                'value' => '60',
                'sentence' => 'votre rythme cardiaque diminue anormalement'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'bloodpressure',
                'operator' => '==',
                'value' => 'high',
                'sentence' => 'votre pression artérielle est anormalement haute'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'weight',
                'operator' => '==',
                'value' => 'strong increase',
                'sentence' => 'vous prenez beaucoup de poids'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'weight',
                'operator' => '==',
                'value' => 'strong decrease',
                'sentence' => 'vous perdez beaucoup de poids'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'sleep',
                'operator' => '==',
                'value' => 'anomalous',
                'sentence' => 'vous avez une période de sommeil anormale'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('motion')->first()->id,
                'attribute' => 'event',
                'operator' => '==',
                'value' => 'fall',
                'sentence' => 'vous chutez'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('motion')->first()->id,
                'attribute' => 'event',
                'operator' => '==',
                'value' => 'inactive',
                'sentence' => 'vous restez dans une position inactive trop longtemps'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('agenda')->first()->id,
                'attribute' => 'visit_last_month',
                'operator' => '<',
                'value' => '2',
                'sentence' => 'vous n\'avez pas reçu de visite depuis longtemps'
            ],
        ]);
    }
}
