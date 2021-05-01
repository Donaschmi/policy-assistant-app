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
        // Assignable
        DB::table('events')->insert([
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'heartbeat',
                'operator' => '>',
                'value' => '130',
                'sentence' => 'votre rythme cardiaque augmente anormalement',
                'mental_health' => 0,
                'physical_health' => 3,
                'reactivity' => 2,
                'privacy' => 3
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'heartbeat',
                'operator' => '<',
                'value' => '60',
                'sentence' => 'votre rythme cardiaque diminue anormalement',
                'mental_health' => 0,
                'physical_health' => 3,
                'reactivity' => 2,
                'privacy' => 3
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'bloodpressure',
                'operator' => '==',
                'value' => 'high',
                'sentence' => 'votre pression artérielle est anormalement haute',
                'mental_health' => 0,
                'physical_health' => 3,
                'reactivity' => 2,
                'privacy' => 3
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'weight',
                'operator' => '==',
                'value' => 'strong increase',
                'sentence' => 'vous prenez beaucoup de poids',
                'mental_health' => 2,
                'physical_health' => 3,
                'reactivity' => 1,
                'privacy' => 3
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'weight',
                'operator' => '==',
                'value' => 'strong decrease',
                'sentence' => 'vous perdez beaucoup de poids',
                'mental_health' => 2,
                'physical_health' => 3,
                'reactivity' => 1,
                'privacy' => 3
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'sleep',
                'operator' => '==',
                'value' => 'anomalous',
                'sentence' => 'vous avez une période de sommeil anormale',
                'mental_health' => 1,
                'physical_health' => 1,
                'reactivity' => 0,
                'privacy' => 3
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('motion')->first()->id,
                'attribute' => 'event',
                'operator' => '==',
                'value' => 'fall',
                'sentence' => 'vous chutez',
                'mental_health' => 0,
                'physical_health' => 3,
                'reactivity' => 3,
                'privacy' => 0
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('motion')->first()->id,
                'attribute' => 'event',
                'operator' => '==',
                'value' => 'inactive',
                'sentence' => 'vous restez dans une position inactive trop longtemps',
                'mental_health' => 1,
                'physical_health' => 1,
                'reactivity' => 1,
                'privacy' => 0
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('agenda')->first()->id,
                'attribute' => 'visit_last_month',
                'operator' => '<',
                'value' => '2',
                'sentence' => 'vous n\'avez pas reçu de visite depuis longtemps',
                'mental_health' => 2,
                'physical_health' => 0,
                'reactivity' => 0,
                'privacy' => 0
            ],
        ]);
    }
}
