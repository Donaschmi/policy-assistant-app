<?php

namespace App\Services;

use App\Assistant\Question;
use App\Exceptions\NoActorsAvailableException;
use App\Exceptions\NoEventsAvailableException;
use App\Models\Action;
use App\Models\Actor;
use App\Models\Event;
use App\Models\Policy;
use App\Models\PolicyActionActor;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class PolicyService
{
    public function store(User $user, $event){
        $policy = $user->policies()->firstOrCreate([
            'event_id' => $event["id"]
        ]);

        foreach ($event["answer"] as $answer){
            $policy->action_actor()->create([
                'action_id' => $event["action"]["id"],
                'actor_id' => $answer["id"]
            ]);
        }
        $policy->load('action_actor.actor', 'event');
        return $policy;
    }
}
