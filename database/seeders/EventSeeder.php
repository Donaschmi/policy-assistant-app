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
                'value' => '130'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'heartbeat',
                'operator' => '<',
                'value' => '60'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('monitoring')->first()->id,
                'attribute' => 'bloodpressure',
                'operator' => '==',
                'value' => 'high'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('motion')->first()->id,
                'attribute' => 'event',
                'operator' => '==',
                'value' => 'fall'
            ],
            [
                'triggerable_type' => Device::class,
                'triggerable_id' => Device::whereName('motion')->first()->id,
                'attribute' => 'event',
                'operator' => '==',
                'value' => 'inactive'
            ],
        ]);
    }
}
