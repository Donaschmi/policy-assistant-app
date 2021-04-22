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
                'assignable' => true,
                'attribute' => 'heartbeat',
                'operator' => '>',
                'value' => '130',
                'sentence' => 'votre rythme cardiaque augmente anormalement'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'assignable' => true,
                'attribute' => 'heartbeat',
                'operator' => '<',
                'value' => '60',
                'sentence' => 'votre rythme cardiaque diminue anormalement'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'assignable' => true,
                'attribute' => 'bloodpressure',
                'operator' => '==',
                'value' => 'high',
                'sentence' => 'votre pression artérielle est anormalement haute'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'assignable' => true,
                'attribute' => 'weight',
                'operator' => '==',
                'value' => 'strong increase',
                'sentence' => 'vous prenez beaucoup de poids'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'assignable' => true,
                'attribute' => 'weight',
                'operator' => '==',
                'value' => 'strong decrease',
                'sentence' => 'vous perdez beaucoup de poids'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'assignable' => true,
                'attribute' => 'sleep',
                'operator' => '==',
                'value' => 'anomalous',
                'sentence' => 'vous avez une période de sommeil anormale'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('motion')->first()->id,
                'assignable' => true,
                'attribute' => 'event',
                'operator' => '==',
                'value' => 'fall',
                'sentence' => 'vous chutez'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('motion')->first()->id,
                'assignable' => true,
                'attribute' => 'event',
                'operator' => '==',
                'value' => 'inactive',
                'sentence' => 'vous restez dans une position inactive trop longtemps'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('agenda')->first()->id,
                'assignable' => true,
                'attribute' => 'visit_last_month',
                'operator' => '<',
                'value' => '2',
                'sentence' => 'vous n\'avez pas reçu de visite depuis longtemps'
            ],
        ]);
        // Non-assignable
        DB::table('events')->insert([
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('door')->first()->id,
                'assignable' => false,
                'attribute' => 'status',
                'operator' => '==',
                'value' => 'opened',
                'sentence' => 'la porte est ouverte'
            ],[
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('door')->first()->id,
                'assignable' => false,
                'attribute' => 'status',
                'operator' => '==',
                'value' => 'closed',
                'sentence' => 'la porte est fermée'
            ],
        ]);
    }
}
