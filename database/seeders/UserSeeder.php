<?php

namespace Database\Seeders;

use App\Models\Action;
use App\Models\Actor;
use App\Models\ActorType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(2)->create();

        $user = User::first();

        $user->actors()->create([
            'actor_type_id' => ActorType::whereName('fils_1')->first()->id,
            'fullname' => 'Guillaume',
            'phone_number' => '123'
            ]);
        $user->actors()->create([
            'actor_type_id' => ActorType::whereName('voisin_1')->first()->id,
            'fullname' => 'Bernard',
            'phone_number' => '123'
        ]);

        $user->policies()->create([
            'event_id' => 1
        ]);
        $user->policies()->create([
            'event_id' => 3
        ]);

        $user->policies->each(function ($item, $key){
           $item->action_actor()->create([
               'action_id' => Action::whereName('call')->first()->id,
               'actor_id' => Actor::first()->id
           ]);
        });
    }
}
